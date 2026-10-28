<template>
  <div class="dashboard">
    <div class="user-information">
      <base-section class="user-info-section stay-box"
        ><font-awesome-icon icon="fa-solid fa-trophy" class="stay-icon" />{{
          user.registeredDays
        }}
        روز با ما هستی</base-section
      >
      <base-section class="user-info-section success-buy-box"
        ><font-awesome-icon
          icon="fa-solid fa-shopping-bag"
          class="success-buy-icon"
        />
        {{ user.paidOrders }} خرید موفق
      </base-section>
      <base-section class="user-info-section comments-box"
        ><font-awesome-icon
          icon="fa-regular fa-commenting"
          class="success-buy-icon"
        />
        <span v-if="user.comments > 0">{{ user.comments }} کامنت گذاشتی</span
        ><span v-else>هنوز کامنت نزاشتی ☹️</span></base-section
      >
    </div>
    <div class="extra-information">
      <base-section class="extra-info-section copy-box"
        ><base-button @click="copyText"
          ><div v-if="!copied">
            <font-awesome-icon icon="fa-solid fa-qrcode" class="copy-icon" />
            برای دریافت 20% تخفیف کلیک کن
          </div>
          <p v-else>
            <font-awesome-icon
              icon="fa-regular fa-copy"
              class="copied-icon"
            />متن کپی شد!
          </p></base-button
        ></base-section
      >
      <base-section class="extra-info-section insta-box"
        ><a href="#"
          ><font-awesome-icon
            icon="fa-brands fa-instagram"
            class="brand-icon"
          />
          پیج اینستاگرام ما رو حتما ببین</a
        ></base-section
      >
    </div>
  </div>
</template>

<script>
import { onMounted, ref } from "vue";
import { http } from "../../utils/http";

export default {
  setup() {
    const textToCopy = ref("norouz 1405");
    const copied = ref(false);

    const user = ref({
      registeredDays: null,
      paidOrders: null,
      comments: null,
    });

    const copyText = async () => {
      try {
        await navigator.clipboard.writeText(textToCopy.value);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2500);
      } catch (err) {
        console.error("Failed to copy text: ", err);
        alert("Failed to copy text.");
      }
    };

    const userData = async () => {
      try {
        const res = await http("/user");

        const data = await res.json();

        if (res.ok) {
          user.value = {
            registeredDays: data.registeredDays,
            paidOrders: data.paidOrders,
            comments: data.approvedComments,
          };
        }
      } catch (error) {
        console.error("Failed to fetch data: ", error);
      }
    };

    onMounted(() => {
      userData();
    });

    return {
      copied,
      copyText,
      user,
    };
  },
};
</script>

<style scoped>
.user-information,
.extra-information {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 16px;
  margin: 16px 0;
}

.user-info-section,
.extra-info-section {
  display: flex;
  align-items: center;
  flex-grow: 1;
  gap: 8px;
  font-size: 20px;
  color: #fff;
  text-align: center;
}

.extra-info-section a,
.extra-info-section button,
.extra-info-section p {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  margin: 0;
}
.extra-info-section a,
.extra-info-section button {
  color: #fff;
}
.extra-info-section p,
.extra-info-section div {
  color: #434343;
}

.extra-info-section button {
  padding: 0.5rem 1rem !important;
  background-color: #fff !important;
  font: inherit !important;
}

.user-info-section {
  flex-direction: column;
  padding: 24px;
  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.55);
  background: transparent;
  color: var(--primary-color);
  border: 2px var(--primary-color) dashed;
}
.user-info-section svg {
  font-size: 32px;
}

.extra-info-section {
  flex-direction: row;
  justify-content: center;
  padding: 28px 20px;
}

.copy-box {
  background-color: #fff;
  border: 2px var(--primary-color) dashed;
  position: relative;
}
.copy-box::before {
  content: "";
  position: absolute;
  left: -2px;
  top: 50%;
  height: 34px;
  width: 17px;
  border-bottom-right-radius: 34px;
  border-top-right-radius: 34px;
  border: 2px dashed var(--primary-color);
  border-left-color: transparent;
  transform: translateY(-50%);
  background-color: #f8fafc;
  overflow: hidden;
  z-index: 2;
}
.copy-box::after {
  content: "";
  position: absolute;
  right: -2px;
  top: 50%;
  height: 34px;
  width: 17px;
  border-bottom-left-radius: 34px;
  border-top-left-radius: 34px;
  border: 2px dashed var(--primary-color);
  border-right-color: transparent;
  transform: translateY(-50%);
  background-color: #f8fafc;
  overflow: hidden;
  z-index: 2;
}

.insta-box {
  background: linear-gradient(200deg, #5a5acb 0%, #dc2a7d 100%);
}

.now-time {
  flex-direction: row-reverse;
  margin-top: auto;
}

@media (max-width: 768px) {
  .extra-information {
    flex-direction: column;
  }
}
</style>