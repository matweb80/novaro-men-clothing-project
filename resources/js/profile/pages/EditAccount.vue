<template>
  <!-- 🌀 Loader -->
  <div v-if="isSaving">
    <base-spinner class="base-spinner"></base-spinner>
  </div>
  <form
    @submit.prevent="
      submitEditAccount(
        userName,
        userEmail,
        userPhoneNumber,
        password,
        password_confirmation
      )
    "
    v-else
    class="profile-form"
  >
    <div class="form-row">
      <div class="form-group">
        <label for="name" class="form-label"> {{ $t("Name") }} </label>
        <keep-alive>
          <input
            id="name"
            type="text"
            name="name"
            class="form-input"
            placeholder="نام کاربری خود را وارد کنید"
            v-model="userName"
          />
        </keep-alive>
        <p v-if="errors.name" class="form-error">
          {{ errors.name }}
        </p>
      </div>
      <div class="form-group">
        <label for="email" class="form-label">
          {{ $t("Email Address") }}
        </label>
        <keep-alive>
          <input
            id="email"
            type="email"
            name="email"
            class="form-input"
            placeholder="آدرس ایمیل خود را وارد کنید"
            v-model="userEmail"
          />
        </keep-alive>
        <p v-if="errors.email" class="form-error">
          {{ errors.email }}
        </p>
      </div>
    </div>
    <div class="form-group">
      <label for="phone" class="form-label"> {{ $t("Phone Number") }} </label>
      <keep-alive>
        <input
          id="phone"
          type="tel"
          name="phone"
          class="form-input phone-input"
          placeholder="شماره تلفن همراه را وارد کنید"
          v-model="userPhoneNumber"
        />
      </keep-alive>
      <p v-if="errors.phone" class="form-error">
        {{ errors.phone }}
      </p>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="password" class="form-label"> {{ $t("Password") }} </label>
        <div class="password-input">
          <keep-alive>
            <input
              id="password"
              :type="isVisiblePass ? 'text' : 'password'"
              name="password"
              class="form-input"
              autocomplete="new-password"
              placeholder="پسورد جدید را وارد کنید"
              v-model="password"
            />
          </keep-alive>
          <span class="show-hide">
            <font-awesome-icon
              :icon="
                isVisiblePass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'
              "
              class="eye-icon"
              @mousedown="isVisiblePass = true"
              @mouseup="isVisiblePass = false"
              @mouseout="isVisiblePass = false"
            />
          </span>
        </div>
        <p v-if="errors.password" class="form-error">
          {{ errors.password }}
        </p>
      </div>
      <div class="form-group">
        <label for="password-confirm" class="form-label">
          {{ $t("Confirm Password") }}
        </label>
        <div class="password-input">
          <keep-alive>
            <input
              id="password-confirm"
              :type="isVisiblePass ? 'text' : 'password'"
              name="password_confirmation"
              class="form-input"
              autocomplete="new-password"
              placeholder="پسورد جدید را تکرار کنید"
              v-model="password_confirmation"
            />
          </keep-alive>
          <span class="show-hide">
            <font-awesome-icon
              :icon="
                isVisiblePass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'
              "
              class="eye-icon"
              @mousedown="isVisiblePass = true"
              @mouseup="isVisiblePass = false"
              @mouseout="isVisiblePass = false"
            />
          </span>
        </div>
        <p v-if="errors.password_confirmation" class="form-error">
          {{ errors.password_confirmation }}
        </p>
      </div>
    </div>
    <!-- password strength bar -->
    <div v-if="password" class="strength-bar">
      <div
        class="strength-fill"
        :style="{ width: passwordStrength.score * 20 + '%' }"
        :class="strengthClass"
      ></div>
    </div>
    <p v-if="password">قدرت رمز: {{ passwordStrength.label }}</p>
    <ul v-if="password">
      <li :class="{ valid: password.length >= 8 }">حداقل ۸ کاراکتر</li>
      <li :class="{ valid: /[A-Z]/.test(password) }">حروف بزرگ</li>
      <li :class="{ valid: /[0-9]/.test(password) }">عدد</li>
      <li :class="{ valid: /[^A-Za-z0-9]/.test(password) }">کاراکتر خاص</li>
    </ul>
    <div class="form-group">
      <base-button class="btn btn--primary" :disabled="infoSaving">{{
        infoSaving ? "در حال ذخیره…" : "ذخیره تغییرات"
      }}</base-button>
    </div>

    <p v-if="infoSaved" class="form-success" aria-live="polite">
      اطلاعات با موفقیت ذخیره شد.
    </p>
  </form>
</template>

<script>
import { computed, onMounted, ref, watch } from "vue";
import { useEditAccount } from "../composables/useEditAccount";

export default {
  setup() {
    const isVisiblePass = ref(false);

    const {
      errors,
      validateForm,
      submitEditAccount,
      fetchUser,
      userName,
      userEmail,
      userPhoneNumber,
      infoSaving,
      infoSaved,
    } = useEditAccount();

    const password = ref(null);
    const password_confirmation = ref(null);

    onMounted(async () => {
      fetchUser();
      validateForm(
        userName.value,
        userEmail.value,
        userPhoneNumber.value,
        password.value,
        password_confirmation.value
      );
    });

    // password strength
    const passwordStrength = computed(() => {
      let score = 0;

      if (password.value.length >= 8) score++;
      if (/[A-Z]/.test(password.value)) score++;
      if (/[a-z]/.test(password.value)) score++;
      if (/[0-9]/.test(password.value)) score++;
      if (/[^A-Za-z0-9]/.test(password.value)) score++;

      let label = "";
      if (score <= 2) label = "ضعیف";
      else if (score === 3) label = "متوسط";
      else if (score === 4) label = "خوب";
      else if (score > 4) label = "قوی";

      return { score, label };
    });

    const strengthClass = computed(() => {
      const score = passwordStrength.value.score;
      if (score <= 2) return "weak";
      if (score === 3) return "medium";
      if (score === 4) return "good";
      return "strong";
    });

    watch(
      () => userName.value,
      () => {
        delete errors.value.name;
      }
    );
    watch(
      () => userEmail.value,
      () => {
        delete errors.value.email;
      }
    );
    watch(
      () => userPhoneNumber.value,
      () => {
        delete errors.value.phone;
      }
    );
    watch(
      () => password.value,
      () => {
        delete errors.value.password;
        delete errors.value.password_confirmation;
      }
    );
    watch(
      () => password_confirmation.value,
      () => {
        delete errors.value.password;
        delete errors.value.password_confirmation;
      }
    );

    return {
      errors,
      submitEditAccount,
      userName,
      userEmail,
      userPhoneNumber,
      password,
      password_confirmation,
      infoSaving,
      infoSaved,
      isVisiblePass,
      passwordStrength,
      strengthClass,
    };
  },
};
</script>

<style scoped>
.password-input {
  position: relative;
}

.show-hide {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-45%);
}

.show-hide svg {
  color: #4d4d4d;
  cursor: pointer;
}

.phone-input {
  direction: rtl;
  text-align: right;
}

.strength-bar {
  width: 100%;
  height: 6px;
  background: #eee;
  border-radius: 4px;
  margin-top: 5px;
}

.strength-fill {
  height: 100%;
  border-radius: 4px;
  transition: 0.3s;
}

.strength-fill.weak {
  background: #dc3545;
}
.strength-fill.medium {
  background: #ffc107;
}
.strength-fill.good {
  background: #0dcaf0;
}
.strength-fill.strong {
  background: #198754;
}

ul {
  color: #dc3545;
  list-style: circle;
}
.valid {
  color: #198754;
  list-style: disc;
  list-style-position: inside;
}
</style>