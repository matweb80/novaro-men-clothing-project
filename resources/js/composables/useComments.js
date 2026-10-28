import { ref } from "vue";
import { http } from "@/utils/http";

export function useComments(product, user) {
    const newComment = ref("");
    const isLoading = ref(false);
    const isVisibleDialog = ref(false);
    const modal = ref({
        message: "",
        headerColor: "",
        buttonColor: "",
        titleText: "",
    });

    async function submitComment() {
        if (!newComment.value.trim()) return;

        try {
            isLoading.value = true;
            const res = await http("/api/comments", {
                method: "POST",
                body: JSON.stringify({
                    user_id: user.id,
                    commentable_type: "App\\Models\\Product",
                    commentable_id: product?.id,
                    comment: newComment.value,
                    parent_id: 0,
                }),
            });

            const data = await res.json();

            if (!res.ok) throw data;

            if (data.success) {
                isVisibleDialog.value = true;

                modal.value = {
                    message: "نظر شما با موفقیت ثبت شد و پس از تایید ادمین در سایت قرار میگیرد. با تشکر از بازخورد شما🌹",
                    headerColor: "var(--accent-color)",
                    buttonColor: "success",
                    titleText: "موفق",
                };

                newComment.value = "";
            }
        } catch (error) {
            isVisibleDialog.value = true;
            modal.value = {
                message:
                    "خطایی در ارسال نظر رخ داده است! لطفا دوباره تلاش کنید" ||
                    error,
                headerColor: "var(--danger-color)",
                buttonColor: "danger",
                titleText: "خطا",
            };
        } finally {
            isLoading.value = false;
            setTimeout(() => {
                isVisibleDialog.value = false;
            }, 5000);
        }
    }

    return {
        submitComment,
        newComment,
        isLoading,
        isVisibleDialog,
        modal,
    };
}
