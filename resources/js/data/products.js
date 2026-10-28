// Product catalog mock data.
// In production this will come from the Laravel API (e.g. GET /api/products)
// without changing the shape consumers rely on:
// { id, name, price, originalPrice?, badge?, inStock, categoryId, sizes, color, popularity }.
//
// Every product carries categoryId (a real CATEGORY_TREE id, at whatever
// depth it naturally belongs), sizes, a single color, and a mock
// popularity rank — this is what powers the Shop page's category
// filtering, Size/Color filters, and "پرفروش‌ترین" sort.
export const PRODUCTS = [
  { id:"p1", name:"کت پشمی دو دکمه", price:12500000, badge:"new", inStock:true, categoryId:"wool-coats-db", sizes:["M","L","XL"], color:"navy", popularity:5 },
  { id:"p2", name:"پیراهن رسمی سفید", price:2850000, inStock:true, categoryId:"formal-shirts", sizes:["S","M","L","XL"], color:"white", popularity:12 },
  { id:"p3", name:"کفش اسپرت چرم", price:4200000, originalPrice:5000000, inStock:true, categoryId:"sneakers", sizes:[], color:"black", popularity:8 },
  { id:"p4", name:"کمربند چرم مشکی", price:1150000, inStock:true, categoryId:"belts", sizes:[], color:"black", popularity:20 },
  { id:"p5", name:"ساعت مچی کلاسیک", price:8900000, badge:"new", inStock:true, categoryId:"watches", sizes:[], color:"charcoal", popularity:3 },
  { id:"p6", name:"شلوار چینو خاکی", price:1950000, inStock:true, categoryId:"chinos", sizes:["S","M","L","XL"], color:"camel", popularity:15 },
  { id:"p7", name:"ژاکت یقه‌گرد پشمی", price:3400000, originalPrice:4000000, inStock:true, categoryId:"knitwear", sizes:["M","L","XL"], color:"olive", popularity:10 },
  { id:"p8", name:"بارانی بلند", price:6750000, inStock:false, categoryId:"trench", sizes:["M","L","XL","XXL"], color:"charcoal", popularity:18 },
  { id:"p9", name:"کیف پول چرم", price:980000, inStock:true, categoryId:"wallets", sizes:[], color:"black", popularity:22 },
  { id:"p10", name:"بوت چرم ساقه‌کوتاه", price:5600000, badge:"new", inStock:true, categoryId:"boots", sizes:[], color:"black", popularity:7 },
  { id:"p11", name:"پیراهن اسپرت طرح‌دار", price:2100000, inStock:true, categoryId:"casual-shirts", sizes:["S","M","L"], color:"cream", popularity:25 },
  { id:"p12", name:"شلوار جین اسلیم", price:2450000, originalPrice:2900000, inStock:true, categoryId:"denim", sizes:["S","M","L","XL"], color:"navy", popularity:9 },

  // Outerwear subtree — expanded so the default Shop category (کت و کاپشن)
  // has enough items to demonstrate real filtering, sorting and pagination.
  { id:"p13", name:"کت پشمی یقه‌شاولی", price:13200000, inStock:true, categoryId:"wool-coats-db", sizes:["M","L","XL"], color:"charcoal", popularity:14 },
  { id:"p14", name:"کت پشمی تک دکمه طوسی", price:11800000, originalPrice:13500000, inStock:true, categoryId:"wool-coats-sb", sizes:["S","M","L"], color:"charcoal", popularity:6 },
  { id:"p15", name:"کت پشمی تک دکمه سرمه‌ای", price:12100000, inStock:true, categoryId:"wool-coats-sb", sizes:["M","L","XL"], color:"navy", popularity:16 },
  { id:"p16", name:"کت پشمی تک دکمه مشکی", price:12400000, inStock:false, categoryId:"wool-coats-sb", sizes:["L","XL"], color:"black", popularity:24 },
  { id:"p17", name:"بارانی کلاسیک شتری", price:7100000, badge:"new", inStock:true, categoryId:"trench", sizes:["S","M","L","XL"], color:"camel", popularity:4 },
  { id:"p18", name:"بارانی کوتاه مشکی", price:6300000, inStock:true, categoryId:"trench", sizes:["M","L"], color:"black", popularity:19 },
  { id:"p19", name:"کاپشن پنبه‌ای زیتونی", price:4650000, inStock:true, categoryId:"jackets", sizes:["S","M","L","XL"], color:"olive", popularity:11 },
  { id:"p20", name:"کاپشن چرم مشکی", price:9200000, originalPrice:10500000, inStock:true, categoryId:"jackets", sizes:["M","L"], color:"black", popularity:2 },
  { id:"p21", name:"کاپشن بادی سرمه‌ای", price:3800000, inStock:true, categoryId:"jackets", sizes:["S","M","L","XL","XXL"], color:"navy", popularity:21 },
  { id:"p22", name:"کاپشن پشمی زغالی", price:5200000, inStock:true, categoryId:"jackets", sizes:["M","L","XL"], color:"charcoal", popularity:13 },
  { id:"p23", name:"بلیزر یقه‌ذوزنقه‌ای مشکی", price:8600000, badge:"new", inStock:true, categoryId:"blazers", sizes:["S","M","L","XL"], color:"black", popularity:1 },
  { id:"p24", name:"بلیزر پشمی سرمه‌ای", price:9100000, inStock:true, categoryId:"blazers", sizes:["M","L","XL"], color:"navy", popularity:17 },
  { id:"p25", name:"بلیزر کتان کرم", price:6700000, originalPrice:7600000, inStock:true, categoryId:"blazers", sizes:["S","M","L"], color:"cream", popularity:23 },
  { id:"p26", name:"بلیزر زرشکی مخملی", price:9800000, inStock:false, categoryId:"blazers", sizes:["M","L"], color:"burgundy", popularity:26 },
  { id:"p27", name:"کت پشمی دو دکمه زیتونی", price:12900000, inStock:true, categoryId:"wool-coats-db", sizes:["L","XL"], color:"olive", popularity:27 },

  // A few extras elsewhere in the tree, for testing category switches.
  { id:"p28", name:"تی‌شرت یقه‌گرد سفید", price:890000, inStock:true, categoryId:"tshirts", sizes:["S","M","L","XL"], color:"white", popularity:28 },
  { id:"p29", name:"کفش رسمی چرم مشکی", price:5100000, inStock:true, categoryId:"formal-shoes", sizes:[], color:"black", popularity:29 },
  { id:"p30", name:"کفش اسپرت طوسی", price:3900000, originalPrice:4500000, inStock:true, categoryId:"sneakers", sizes:[], color:"charcoal", popularity:30 }
];

// Homepage curations — reference PRODUCTS by id rather than duplicating
// product data, so the same catalog powers every section.
export const NEW_ARRIVAL_IDS = ["p1","p5","p10","p3","p7","p12"];
export const BEST_SELLER_IDS = ["p2","p4","p6","p9"];
