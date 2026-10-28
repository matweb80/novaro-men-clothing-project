<script setup>
import LegalDocLayout from '@/components/layout/LegalDocLayout.vue';

const PLACEHOLDER = 'این بخش هنوز توسط تیم نوواررو تکمیل نشده است و نیاز به بررسی حقوقی دارد.';

const SECTIONS = [
  { id: 'acceptance', title: 'پذیرش قوانین', text: PLACEHOLDER },
  { id: 'account', title: 'حساب کاربری', text: PLACEHOLDER },
  { id: 'orders-payment', title: 'سفارش و پرداخت', text: PLACEHOLDER },
  { id: 'pricing', title: 'قیمت‌ها و موجودی محصولات', text: PLACEHOLDER },
  { id: 'ip', title: 'مالکیت معنوی', text: PLACEHOLDER },
  { id: 'liability', title: 'محدودیت مسئولیت', text: PLACEHOLDER },
  { id: 'governing-law', title: 'قوانین قابل اجرا', text: PLACEHOLDER },
  { id: 'changes', title: 'تغییرات در این قوانین', text: PLACEHOLDER },
  { id: 'contact', title: 'تماس با ما', text: PLACEHOLDER },
];
</script>

<template>
  <LegalDocLayout
    title="قوانین و مقررات"
    lead="این صفحه هنوز یک پیش‌نویس ساختاری است؛ متن نهایی باید توسط تیم نوواررو تکمیل و پیش از انتشار توسط مشاور حقوقی بررسی شود."
    :sections="SECTIONS"
  />
</template>
