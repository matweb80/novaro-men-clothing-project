<template>
  <teleport to="body">
    <div v-if="show" @click="tryClose" class="backdrop"></div>
    <transition name="drawer">
      <dialog open v-if="show">
        <menu v-if="!fixed">
          <slot name="actions">
            <div class="drawer-header">
              <h4>سبد خرید</h4>
              <span @click="tryClose" class="close-button">X</span>
            </div>
          </slot>
        </menu>
        <section>
          <slot>
            <div class="drawer-body">
              <div
                v-if="cart.items.length === 0"
                class="empty-cart"
              >
                <img
                  class="img-fluid object-fit-cover"
                  src="../../../images/empty-cart.webp"
                  alt="empty cart"
                  loading="lazy"
                />
                سبد خرید خالی است
              </div>
              <div
                v-for="item in cart.items"
                :key="item.product.id"
                class="cart-item"
              >
                <img
                  :src="item.product.image"
                  :alt="item.product.title"
                  class="item-img"
                  loading="lazy"
                />
                <a :href="'products/' + item.product.slug" class="item-title">{{
                  item.product.title
                }}</a>
                <select
                  class="form-control text-center item-select"
                  @change="
                    changeQuantity(
                      item.id,
                      $event.target.value,
                      item.product.slug,
                      'my-cart'
                    )
                  "
                >
                  <option
                    v-for="index in item.product.inventory"
                    :key="index"
                    :value="index"
                    :selected="item.quantity == index"
                  >
                    {{ index }}
                  </option>
                </select>
                <span v-if="item.product.is_on_special_sale" class="item-price"
                  >{{
                    (item.product.sale_price * item.quantity).toLocaleString()
                  }}<br />تومان</span
                >
                <span v-else class="item-price"
                  >{{ (item.product.price * item.quantity).toLocaleString()
                  }}<br />تومان</span
                >
                <font-awesome-icon
                  icon="fa-solid fa-close"
                  class="close-icon text-danger"
                  @click="removeFromCart(item.id)"
                />
                <!-- mini dialog -->
                <base-mini-dialog
                  :show="isVisibleDialog"
                  :message="modal.message"
                  :type="modal.type"
                  @close="isVisibleDialog = false"
                ></base-mini-dialog>
              </div>
              <cart-summary-details
                v-if="cart.items.length !== 0"
              ></cart-summary-details>
            </div>
            <footer class="drawer-footer" v-if="cart.items.length !== 0">
              <a href="/cart"
                ><base-button class="base-button cart-view"
                  >مشاهده سبد خرید</base-button
                ></a
              >
              <a href="/checkout">
                <base-button class="base-button checkout-view active"
                  >تسویه حساب</base-button
                ></a
              >
            </footer>
          </slot>
        </section>
      </dialog>
    </transition>
  </teleport>
</template>

<script>
import { onMounted } from "vue";
import { useCart } from "../../hooks/useCart";
import CategoriesAccordion from "../header/CategoriesAccordion.vue";
import CartSummaryDetails from "../partials/CartSummaryDetails.vue";
export default {
  components: {
    CategoriesAccordion,
    CartSummaryDetails,
  },
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    fixed: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  emits: ["close"],
  setup(props, context) {
    function tryClose() {
      if (props.fixed) {
        return;
      }
      context.emit("close");
    }

    const {
      cart,
      isVisibleDialog,
      modal,
      loadCart,
      changeQuantity,
      removeFromCart,
    } = useCart();

    onMounted(() => {
      if (!cart.loaded) {
        loadCart();
      }
    });

    return {
      tryClose,
      cart,
      isVisibleDialog,
      modal,
      changeQuantity,
      removeFromCart,
    };
  },
};
</script>

<style scoped>
.backdrop {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.75);
  z-index: var(--nv-z-cart-backdrop);
}

dialog {
  position: fixed;
  top: 0;
  right: 70%;
  width: 30%;
  z-index: var(--nv-z-cart);
  border-radius: 0 12px 12px 0;
  border: none;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  padding: 0;
  margin: 0;
  background-color: white;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.close-button {
  cursor: pointer;
  padding: 0.5rem;
  font-size: 24px;
}

menu {
  padding: 1rem;
  display: flex;
  flex-direction: column;
}
menu .drawer-header {
  display: flex;
  flex-direction: row-reverse;
  justify-content: space-between;
  align-items: center;
  margin: 0;
}

.cart-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-inline-end: 30px;
  background: #ffffff;
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 1) 50%,
    rgba(241, 241, 241, 1) 100%
  );
  padding: 10px 10px;
  border-radius: 8px;
  margin-bottom: 4px;
  border-right: 2px solid var(--primary-color) !important;
  border-bottom: 2px solid var(--primary-color);
}

section {
  padding: 0 0.75rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  overflow: hidden;
}

section .drawer-body {
  padding: 0 0.5rem 1rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin: 0 0 16px;
  overflow-y: auto !important;
  overflow-x: hidden !important;
}
section .drawer-body .empty-cart{
  color: var(--c-text-muted);
  text-align: center;
}

section .drawer-footer {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 2rem;
}

.item-img {
  height: 50px;
  width: 50px;
  object-fit: cover;
}

.base-button {
  width: 100%;
}

a {
  text-decoration: none;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

a.item-title {
  flex-basis: 150px;
}

.base-button.cart-view {
  color: var(--primary-color);
}
.base-button.cart-view:hover {
  color: white;
}

select.item-select {
  width: 45px;
}

.item-price {
  text-align: center;
  flex-basis: 80px;
}

.total-price,
.special-sale-value {
  display: flex;
  justify-content: space-between;
  padding-top: 12px;
}

.close-icon {
  cursor: pointer;
  font-size: 16px;
}

.drawer-enter-from,
.drawer-leave-to {
  opacity: 0;
  transform: translateX(-250px);
}

.drawer-enter-active {
  transition: all 0.3s ease-out;
}

.drawer-leave-active {
  transition: all 0.3s ease-in;
}

.drawer-enter-to,
.drawer-leave-from {
  opacity: 1;
  transform: translateX(0);
}

@media (min-width: 992px) and (max-width: 1300px) {
  dialog {
    right: 60%;
    width: 40%;
  }
}

@media (max-width: 992px) {
  dialog {
    right: 55%;
    width: 45%;
  }
  a {
    -webkit-line-clamp: 3;
  }
}

@media (max-width: 768px) {
  .item-price {
    font-size: 12px;
  }

  section {
    padding-right: 0.15rem;
    padding-left: 0.45rem;
  }

  section .drawer-body {
    padding: 0 0.25rem;
  }

  .item-img {
    height: 40px;
    width: 40px;
  }

  a.item-title {
    font-size: 12px;
  }
}

@media (max-width: 490px) {
  dialog {
    right: 35%;
    width: 65%;
  }
}
</style>
