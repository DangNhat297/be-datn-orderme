<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Egulias\EmailValidator\Warning\CFWSNearAt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    protected $user;
    protected $dishes;
    protected $orders;
    protected $category;
    protected $program;

    function __construct(Dishes $dishes, User $users, Order $orders, Category $category, Program $program)
    {
        $this->user = $users;
        $this->dishes = $dishes;
        $this->orders = $orders;
        $this->category = $category;
        $this->program = $program;
    }


    /**
     * @OA\Get(
     *      path="/admin/statistical",
     *      operationId="getStatistical",
     *      tags={"Statistical"},
     *      summary="Get list of statistical",
     *      description="Returns list of statistical",
     *      @OA\Parameter(
     *          name="sort",
     *          description="filter by month , day ,week example : sort=day,moth,week",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="duration",
     *          description="filter by duration example: duration= -7 => 7 day ago || duration= 7 => +7 day  ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="column",
     *          description="show by column ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="start_date",
     *          description="filter by day start_date ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="end_date",
     *          description="filter by day end_date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/StatisticResponse"),
     *       ),
     *     )
     */
    function index(Request $request): JsonResponse
    {

        switch ($request->sort) {
            case 'day':
                $listData = $this->byDay($request);
                break;
            case 'week':
                $listData = $this->byWeek($request);
                break;
            case 'month':
                $listData = $this->byMonth($request);
                break;
            default:
                $listData = $this->byDay($request);
                break;
        }

        return $this->sendSuccess($listData);
    }

    function byDay($request)
    {
        $duration = $request->duration ?? $request->duration = "-7";
        $column = $request->column ?? str_replace('-', '', $request->duration);
        $listDay = array();
        $listData = [];

        //today
        if ($duration == 0) {
            $d = date("Y-m-d", strtotime('today'));
            $listDay[] = $d;
        } else {
            for ($i = 0; $i <= $column; $i++) {
                $d = date("Y-m-d", strtotime("$duration day + $i day"));
                $listDay[] = $d;
            }
        }
//           select from to
        if ($request->start_date && $request->end_date) {
            $array = createDateRangeArray($request->start_date, $request->end_date);
            $listDay = $array;
        }

        foreach ($listDay as $day) {
            $total_money = 0;
            $listDishes = [];
            $Order = $this->orders->newQuery()
                ->with('dishes')
                ->whereDate('created_at', $day)
                ->get();
            foreach ($Order as $row_orrder) {
                $listDishes = $this->getProductStatistic($Order);
//                return $this->sendSuccess($Order);

            }

            foreach ($listDishes as $list) {
                $total_money += $list->total;
            }

            $listData[] = [
                'duration' => $day,
                'dishes' => $listDishes,
                'total_money' =>$total_money,
            ];

        }
        return $listData;
    }

    public function getProductStatistic($orders)
    {
        $products = $orders->reduce(fn($init, $order) => $init->merge($order->dishes), collect([]))
            ->transform(fn($p) => $p->makeHidden(['pivot', 'created_at', 'updated_at', 'slug', "description", "content", 'quantity', 'category_id', 'status']))
            ->unique('id')
            ->values();


        $products->transform(function ($product) use ($orders) {

            $product->quantity_buy = $orders->reduce(function ($init, $order) use ($product) {
                return $init += $order->dishes
                    ->where('id', $product->id)
                    ->sum(fn($d) => $d->pivot->quantity);
            }, 0);

            $product->total = $product->quantity_buy * ($product->pivot->price - $product->pivot->price_sale);
            return $product;
        });
        return $products;
    }


    function byWeek($request)
    {
        $duration = $request->duration ?? '-2';
        $column = $request->column ?? str_replace('-', '', $request->duration);
        $countColumn = $request->duration ? $column : 2;
        $listWeekday = array();
        $listData = [];

        if($duration==0){
                $d = date("Y-m-d", strtotime("this week"));
                $listWeekday[] = $d;
        }else{
            for ($i = 0; $i < $countColumn; $i++) {
                $d = date("Y-m-d", strtotime("$duration week + $i week"));
                $listWeekday[] = $d;
            }
        }


        foreach ($listWeekday as $week) {
            $totalWeek = 0;
            $listDishes = [];
            $datetime = date("Y-m-d", strtotime($week . " + 7 day"));

            $Order = $this->orders->newQuery()->with('dishes')->whereBetween('created_at', [$week, $datetime])->get();
            foreach ($Order as $row_orrder) {
                $listDishes = $this->getProductStatistic($Order);
            }
            foreach ($listDishes as $list) {
                $totalWeek += $list->total;
            }
            $listData[] = [
                'duration' => $week . ' - ' . $datetime,
                'dishes' => $listDishes,
                'total_money' => $totalWeek
            ];
        }

        return $listData;
    }

    function byMonth($request)
    {
        $duration = $request->duration ?? '-2';
        $column = str_replace('-', '', $request->duration);
        $countColumn = $request->duration ? $column : 2;
        $listMonth = array();
        $listData = [];
        $listDay = array();
        $d = getdate();
        $year = $request->year ?? $d['year'];
//        $month = $request->month ?? $d['mon'];
//        $thisMonth = "$year-$month";
        if ($duration == 0) {
            $d = date('Y-m', strtotime('this months'));
            $listMonth[] = $d;
        } else {
            for ($i = 0; $i <= $countColumn; $i++) {
                $d = date('Y-m', strtotime("$duration months + $i months "));
                $listMonth[] = $d;
            }
        }

        foreach ($listMonth as $month) {
            $monthCurrent = Carbon::parse($month)->format('m');
            $daysInMonth = Carbon::parse($month)->daysInMonth;

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $time = mktime(12, 0, 0, $monthCurrent, $d, $year);
                $listDay[] = date('Y-m-d', $time);
            }

            $listDishes = [];
            foreach ($listDay as $day) {

                $Order = $this->orders->newQuery()
                    ->with('dishes')
                    ->whereDate('created_at', $day)
                    ->get();
                foreach ($Order as $row_orrder) {
                    $listDishes[] = $this->getProductStatistic($Order);
                }
            }

            $listDishes = collect($listDishes)->reduce(fn($init, $dishes) => $init->merge($dishes), collect([]));
            $newList = $listDishes->reduce(function ($init, $dish) use ($listDishes) {
                if (!$init->contains('id', $dish->id)) {
                    $dishes = $listDishes->filter(fn($val) => $dish->id == $val->id);
                    $dish->quantity_buy = $dishes->sum('quantity_buy');
                    $dish->price = $dishes->first()->price ;
                    $dish->total = $dish->quantity_buy * ($dish->pivot->price - $dish->pivot->price_sale);
                    return $init->push($dish);
                }
                return $init;
            }, collect([]));

            $totalMoney = $newList->sum('total');
            $listData[] = [
                'duration' => $month,
                'dishes' => $newList,
                'total_money' => $totalMoney
            ];

        }
        return $listData;
    }

    /**
     * @OA\Get(
     *      path="/admin/statistical/all-table",
     *      operationId="getStatisticalAllTable",
     *      tags={"Statistical"},
     *      summary="Get list of statistical all table",
     *      description="Returns list of statistical all table",
     *      @OA\Parameter(
     *          name="start_date",
     *          description="filter by day start_date ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="end_date",
     *          description="filter by day end_date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/StatisticTopProductResponse"),
     *       ),
     *     )
     */
    function statistical_count_table(Request $request)
    {
        $orders = $this->orders->newQuery()
            ->findByDateRange($request)
            ->with('dishes')->get();
        $products = $orders->reduce(fn($init, $order) => $init->merge($order->dishes), collect([]))
            ->transform(fn($p) => $p->makeHidden(['pivot', 'created_at', 'updated_at', 'slug', "description", "content", 'quantity', 'category_id', 'status']))
            ->unique('id')
            ->values();

//            start
        $products->transform(function ($product) use ($orders) {
            $product->quantity_buy = $orders->reduce(function ($init, $order) use ($product) {
                return $init += $order->dishes
                    ->where('id', $product->id)
                    ->sum(fn($d) => $d->pivot->quantity);
            }, 0);
            $product->total = $product->quantity_buy * ($product->price - $product->pivot->price_sale);
            return $product;
        }, collect([]));

        $topList = $products->sortByDesc('quantity_buy'); // hight to low
//        return $this->sendSuccess($topList);

        $topNotSelling = $products;

        $allDishes = $this->dishes->newQuery()->get();

        $allDishes->each(function ($dish) use ($topNotSelling) {
            if (!$topNotSelling->contains('id', $dish->id)) {
                $dish->quantity_buy = 0;
                $dish->total = 0;
                $dish->makeHidden(['pivot', 'created_at', 'updated_at', 'slug', "description", "content", 'quantity', 'category_id', 'status']);
                $topNotSelling->push($dish);
            }
        });


        $topNotSelling = $topNotSelling->sortBy('quantity_buy')->values()->slice(0, 5); // loww to hight

//        return $this->sendSuccess($topNotSelling);


        $data = [
            'users' => $this->user->newQuery()->count(),
            'dishes' => $this->dishes->newQuery()->count(),
            'categories' => $this->category->newQuery()->count(),
            'orders' => $this->orders->newQuery()->count(),
            'topSelling' => $topList->values()->slice(0, 5),
            'topNotSelling' => $topNotSelling
        ];
//            end  hiện thì dạng object
        return $this->sendSuccess($data);
    }


    /**
     * @OA\Get(
     *      path="/admin/statistical/category-table",
     *      operationId="getStatisticalCategoryTable",
     *      tags={"Statistical"},
     *      summary="Get list of statistical category table ",
     *      description="Returns list of statistical category table ",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/StatisticCategoryResponse"),
     *       ),
     *     )
     */
    function statistical_table_category()
    {
        $category = $this->category->newQuery()->select('id', 'name')->withCount('dishes')->get();
        return $this->sendSuccess($category);
    }


    /**
     * @OA\Get(
     *      path="/admin/statistical/flash-sale",
     *      operationId="getStatisticalFlashSale",
     *      tags={"Statistical"},
     *      summary="Get list of statistical flash sale",
     *      description="Returns list of statistical flash sale",
     *      @OA\Parameter(
     *          name="start_date",
     *          description="filter by day start_date ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="end_date",
     *          description="filter by day end_date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="limit size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/StaticFlashsaleResponse"),
     *       ),
     *     )
     */
    function flashSale_product_statistics(Request $request)
    {
        $pageSize = $request->limit ?: PAGE_SIZE_DEFAULT;
        $flashSales = $this->program->newQuery()
            ->where('status', ENABLE)
            ->findByDate($request)
            ->with('dishes')
            ->paginate($pageSize);


        $flashSales->getCollection()->transform(function ($flash_sale) {
            $flash_sale->makeHidden(['dishes', 'created_at', 'updated_at', 'description', 'banner']);

            $orders = $this->orders
                ->newQuery()
                ->whereBetween('created_at', [ $flash_sale->start_date,$flash_sale->end_date])
                ->with('dishes')
                ->get();

            $dishes = $orders->reduce(fn($init, $order) => $init->merge($order->dishes), collect([]))
                ->transform(function($dish) {
                    $dish->makeHidden(['pivot', 'created_at', 'updated_at', 'slug', "description", "content", 'quantity', 'category_id', 'status']);
                    $dish->price = $dish->pivot->price - $dish->pivot->price_sale;
                    return $dish;
                })
                ->unique('id')
                ->values();

            $flash_sale->dish_in_flashsale =
                $dishes->transform(function ($product) use ($orders) {
                    $product->quantity_buy = $orders->reduce(function ($init, $order) use ($product) {
                        return $init += $order->dishes
                            ->where('id', $product->id)
                            ->sum(fn($d) => $d->pivot->quantity);
                    }, 0);

                    $product->total = $product->quantity_buy * $product->price ;
                    return $product;
                }, collect([]));
//                  $dishes->filter(fn ($dish) => $flash_sale->dishes->contains('id', $dish->id));
            $flash_sale->total_flashSale = $flash_sale->dish_in_flashsale->sum('total');
            return $flash_sale;
        });


        return $this->sendSuccess($flashSales);

    }

}

