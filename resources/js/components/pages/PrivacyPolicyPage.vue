<script setup>
import LegalDocLayout from '@/components/layout/LegalDocLayout.vue';

const PLACEHOLDER = 'این بخش هنوز توسط تیم نوواررو تکمیل نشده است و نیاز به بررسی حقوقی دارد.';

const SECTIONS = [
  { id: 'intro', title: 'مقدمه', text: PLACEHOLDER },
  { id: 'data-collected', title: 'اطلاعاتی که جمع‌آوری می‌کنیم', text: PLACEHOLDER },
  { id: 'data-usage', title: 'نحوه‌ی استفاده از اطلاعات', text: PLACEHOLDER },
  { id: 'data-sharing', title: 'اشتراک‌گذاری اطلاعات با اشخاص ثالث', text: PLACEHOLDER },
  { id: 'cookies', title: 'کوکی‌ها', text: PLACEHOLDER },
  { id: 'security', title: 'امنیت اطلاعات', text: PLACEHOLDER },
  { id: 'user-rights', title: 'حقوق کاربر (دسترسی، اصلاح، حذف اطلاعات)', text: PLACEHOLDER },
  { id: 'changes', title: 'تغییرات در این سیاست', text: PLACEHOLDER },
  { id: 'contact', title: 'تماس با ما', text: PLACEHOLDER },
];
</script>

<template>
  <LegalDocLayout
    title="حریم خصوصی"
    lead="این صفحه هنوز یک پیش‌نویس ساختاری است؛ متن نهایی باید توسط تیم نوواررو تکمیل و پیش از انتشار توسط مشاور حقوقی بررسی شود."
    :sections="SECTIONS"
  />
</template>
