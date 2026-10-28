// Color swatches — muted, brand-appropriate natural tones only.
export const COLORS = [
  { id: 'navy', name: 'سرمه‌ای', hex: '#0F172A' },
  { id: 'charcoal', name: 'زغالی', hex: '#33363D' },
  { id: 'black', name: 'مشکی', hex: '#111214' },
  { id: 'cream', name: 'کرم', hex: '#E8E1D3' },
  { id: 'camel', name: 'شتری', hex: '#B08968' },
  { id: 'olive', name: 'زیتونی', hex: '#6B6E4E' },
  { id: 'white', name: 'سفید', hex: '#FFFFFF' },
  { id: 'burgundy', name: 'زرشکی', hex: '#6E2A32' },
]

export const SIZES = ['S', 'M', 'L', 'XL', 'XXL']

export const SORT_OPTIONS = [
  { id: 'newest', label: 'جدیدترین' },
  { id: 'bestselling', label: 'پرفروش‌ترین' },
  { id: 'cheapest', label: 'ارزان‌ترین' },
  { id: 'expensive', label: 'گران‌ترین' },
  { id: 'discount', label: 'بیشترین تخفیف' },
]

export const PAGE_SIZE = 12

// Shown under the category H1 for categories that have editorial copy.
export const CATEGORY_DESCRIPTIONS = {
  outerwear: 'کت، کاپشن و بلیزرهایی که خط اصلی پوشش نوواررو را می‌سازند؛ ساخته‌شده برای دوام در فصل‌های سرد.',
  clothing: 'مجموعه کامل پوشاک نوواررو، از پیراهن رسمی تا کاپشن‌های روزمره.',
  shoes: 'کفش‌های رسمی و اسپرت، طراحی‌شده برای همراهی روزانه.',
  اکسسوری: 'جزئیاتی که یک استایل را کامل می‌کنند.',
}

// Default category shown when no ?cat= is present or it doesn't resolve.
export const DEFAULT_CATEGORY_ID = 'outerwear'
