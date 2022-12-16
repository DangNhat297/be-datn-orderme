<?php

const PAGE_SIZE_DEFAULT = 5;

const ORDER_CANCEL = 0; // đơn hàng hủy

const ORDER_PENDING = 1; // chờ xác nhận
 
const ORDER_COOKING = 2; // đang nấu

const ORDER_WAIT_FOR_SHIPPING = 3; // đợi ship

const ORDER_SHIPPING = 4; // đang ship

const ORDER_COMPLETE = 5; // hoàn thành

const ORDER_PAYMENT_COD = 1; // thanh toán sau

const ORDER_PAYMENT_VNPAY = 2; // thanh toán vnpay

const ORDER_PAYMENT_WAITING = 0; // chờ thanh toán

const ORDER_PAYMENT_SUCCESS = 1; // thanh toán thành công

const ORDER_PAYMENT_REFUND = 2; // hoàn tiền

const ENABLE = 1;

const DISABLE = 0;

const DISCOUNT_PERCENT = 1;

const DISCOUNT_PRICE = 2;
