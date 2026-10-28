// Recursive category tree — depth is NOT hardcoded anywhere that consumes this.
// One branch is taken to level 5 deliberately to validate deep recursion
// (Clothing → Outerwear → Coats → Wool Coats → Double/Single Breasted).
//
// This is the single source of truth for Header/DesktopNavigation,
// MobileNavigation, the homepage CategoryShowcase, and the Footer category
// list — none of them should keep their own copy of category data.
//
// In production this will be replaced by a fetch to the Laravel API
// (e.g. GET /api/categories) without changing the shape consumers rely on:
// { id, name, children? }.
export const CATEGORY_TREE = [
  {
    id: 'clothing',
    name: 'پوشاک',
    children: [
      {
        id: 'outerwear',
        name: 'کت و کاپشن',
        children: [
          {
            id: 'coats',
            name: 'کت',
            children: [
              {
                id: 'wool-coats',
                name: 'کت پشمی',
                children: [
                  { id: 'wool-coats-db', name: 'کت پشمی دو دکمه' },
                  { id: 'wool-coats-sb', name: 'کت پشمی تک دکمه' },
                ],
              },
              { id: 'trench', name: 'بارانی' },
            ],
          },
          { id: 'jackets', name: 'کاپشن' },
          { id: 'blazers', name: 'بلیزر' },
        ],
      },
      {
        id: 'shirts',
        name: 'پیراهن',
        children: [
          { id: 'formal-shirts', name: 'پیراهن رسمی' },
          { id: 'casual-shirts', name: 'پیراهن اسپرت' },
        ],
      },
      { id: 'tshirts', name: 'تی‌شرت و پولوشرت' },
      { id: 'knitwear', name: 'ژاکت و پلیور' },
      {
        id: 'trousers',
        name: 'شلوار',
        children: [
          { id: 'chinos', name: 'شلوار چینو' },
          { id: 'denim', name: 'شلوار جین' },
        ],
      },
    ],
  },
  {
    id: 'shoes',
    name: 'کفش',
    children: [
      { id: 'sneakers', name: 'کفش اسپرت' },
      { id: 'formal-shoes', name: 'کفش رسمی' },
      { id: 'boots', name: 'بوت' },
    ],
  },
  {
    id: 'accessories',
    name: 'اکسسوری',
    children: [
      { id: 'belts', name: 'کمربند' },
      { id: 'wallets', name: 'کیف پول' },
      { id: 'watches', name: 'ساعت' },
    ],
  },
  { id: 'collections', name: 'کالکشن‌ها' },
  { id: 'new-arrivals', name: 'جدیدترین‌ها' },
  { id: 'sale', name: 'حراج' },
]

// Only categories with an approved, clean product photo get a real image —
// "Shoes" and "Accessories" have no suitable asset yet (see delivery notes)
// and intentionally keep the placeholder plate rather than a mismatched or
// logo-bearing photo.
export const CATEGORY_IMAGES = {
  clothing: {
    src: 'images/homepage/category-outerwear.webp',
    alt: 'مرد با بلیزر راه‌راه، نمای نیم‌رخ',
  },
  shoes: {
    src: 'images/homepage/category-shoes.webp',
    alt: 'کفش مردانه، نمای تمام رخ',
  },
  accessories: {
    src: 'images/homepage/category-accessories.webp',
    alt: 'اکسسوری مردانه',
  },
}

// Featured Categories on the homepage reuse the top level of the same
// recursive CATEGORY_TREE used by navigation — no separate category data.
export const FEATURED_CATEGORY_IDS = ['clothing', 'shoes', 'accessories']
