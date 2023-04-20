jQuery(document).ready(function () {
    jQuery(document).on("click", ".chat .reply", function (event) {
        event.preventDefault();

        jQuery(document).find("#message input[name=replyTo]").val(jQuery(this).closest(".row").attr("rel-data-id"));
        jQuery(document).find("#message input[name=messageId]").val("");
        jQuery(document).find("#message input[name=removeId]").val("");

        if (jQuery(this).closest(".row").find(".message").text() != "") {
            jQuery(document)
                .find("#message #reply-board small")
                .text(jQuery(this).closest(".row").find(".message").text());
            jQuery(document).find("#message #reply-board").addClass("d-block").removeClass("d-none");
        } else {
            jQuery(document).find("#message #reply-board").addClass("d-none").removeClass("d-block");
            jQuery(document).find("#message #reply-board small").text("");
        }

        jQuery(document).find("#message input[name=messageText]").val("");
        jQuery(document).find("#message #cancel").addClass("d-block").removeClass("d-none");
        jQuery(document).find("#message button[type=submit]").text("Отправить");

        return false;
    });

    jQuery(document).on("click", ".chat .edit", function (event) {
        event.preventDefault();

        jQuery(document).find("#message input[name=replyTo]").val("");
        jQuery(document).find("#message input[name=messageId]").val(jQuery(this).closest(".row").attr("rel-data-id"));
        jQuery(document).find("#message input[name=removeId]").val("");

        if (jQuery(this).closest(".row").find(".message").text() != "") {
            jQuery(document)
                .find("#message #reply-board small")
                .text(jQuery(this).closest(".row").find(".message").text());
            jQuery(document)
                .find("#message input[name=messageText]")
                .val(jQuery(this).closest(".row").find(".message").text());
            jQuery(document).find("#message #reply-board").addClass("d-block").removeClass("d-none");
        } else {
            jQuery(document).find("#message #reply-board").addClass("d-none").removeClass("d-block");
            jQuery(document).find("#message input[name=messageText]").val("");
            jQuery(document).find("#message #reply-board small").text("");
        }

        jQuery(document).find("#message #cancel").addClass("d-block").removeClass("d-none");
        jQuery(document).find("#message button[type=submit]").text("Сохранить");

        return false;
    });

    jQuery(document).on("click", ".chat .delete", function (event) {
        event.preventDefault();

        jQuery(document).find("#message input[name=replyTo]").val("");
        jQuery(document).find("#message input[name=messageId]").val(jQuery(this).closest(".row").attr("rel-data-id"));
        jQuery(document).find("#message input[name=removeId]").val(jQuery(this).closest(".row").attr("rel-data-id"));

        jQuery(document).find("#message #reply-board").addClass("d-none").removeClass("d-block");
        jQuery(document).find("#message input[name=messageText]").val("");
        jQuery(document).find("#message #reply-board small").text("");
        jQuery(document).find("#message #cancel").addClass("d-none").removeClass("d-block");
        jQuery(document).find("#message button[type=submit]").text("Отправить");

        jQuery(document).find("#message").submit();

        return true;
    });

    jQuery(document).on("click", "#message #cancel", function (event) {
        event.preventDefault();

        jQuery(document).find("#message input[name=replyTo]").val("");
        jQuery(document).find("#message input[name=messageId]").val("");
        jQuery(document).find("#message input[name=removeId]").val("");

        jQuery(document).find("#message #reply-board").addClass("d-none").removeClass("d-block");
        jQuery(document).find("#message input[name=messageText]").val("");
        jQuery(document).find("#message #reply-board small").text("");
        jQuery(document).find("#message #cancel").addClass("d-none").removeClass("d-block");
        jQuery(document).find("#message button[type=submit]").text("Отправить");

        return false;
    });

    jQuery(document).on("click", "#message button[type=submit]", function (event) {
        event.preventDefault();

        if (jQuery(document).find("#message input[name=messageText]").val() == "") {
            alert("Сначала необходимо написать сообщение");

            return false;
        }

        jQuery(document).find("#message").submit();

        return true;
    });
});
