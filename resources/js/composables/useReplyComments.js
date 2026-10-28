import { ref } from "vue";
import { http } from "@/utils/http";

export function useReplyComments(user) {
    const replyText = ref("");
    const replyingTo = ref(null);
    const isLoading = ref(false);
    const message = ref("");

    function openReply(id) {
        replyingTo.value = id;
    }

    async function submitReply() {
        if (!replyText.value.trim()) return;
        try {
            isLoading.value = true;
            const res = await http("/api/comments", {
                method: "POST",
                body: JSON.stringify({
                    user_id: user.id,
                    commentable_type: "App\\Models\\Product",
                    commentable_id: window.currentProductId, // در Vue parent تنظیم می‌کنیم
                    comment: replyText.value,
                    parent_id: replyingTo.value,
                }),
            });
            const data = await res.json();
            if (data.success) {
                message.value =
                    "نظر شما با موفقیت ثبت شد و پس از تایید ادمین در سایت قرار میگیرد. با تشکر از بازخورد شما🌹";
                replyText.value = "";
                setTimeout(() => {
                    replyingTo.value = null;
                    message.value = "";
                }, 5000);
            }
        } catch (error) {
            message.value =
                "خطایی در ارسال نظر رخ داده است! لطفا دوباره تلاش کنید" ||
                error;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        replyText,
        replyingTo,
        isLoading,
        message,
        openReply,
        submitReply,
    };
}
