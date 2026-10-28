const FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹']

/** Convert every ASCII digit in `input` to its Persian digit equivalent. */
export function toFa(input) {
  return String(input).replace(/[0-9]/g, (d) => FA_DIGITS[d])
}

/** Format a number as a Toman price string with Persian digits and separators. */
export function formatToman(n) {
  const withSeparators = Number(n).toLocaleString('en-US').replace(/,/g, '٬')
  return toFa(withSeparators) + ' تومان'
}

/** Round-trip discount percentage between an original and current price. */
export function discountPercent(price, originalPrice) {
  return Math.round((1 - price / originalPrice) * 100)
}
