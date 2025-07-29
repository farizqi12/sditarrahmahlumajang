document.addEventListener("DOMContentLoaded", function () {
    var toastElList = [].slice.call(document.querySelectorAll(".toast"));

    toastElList.forEach(function (toastEl) {
        // Initialize toast with 4 seconds delay
        var toast = new bootstrap.Toast(toastEl, {
            delay: 4000,
            animation: false, // We'll handle animation manually
        });

        // Show toast with animation
        toastEl.classList.add("show");
        toast.show();

        // Handle hiding animation
        toastEl.addEventListener("hide.bs.toast", function () {
            toastEl.classList.remove("show");
            toastEl.classList.add("hiding");

            // Remove element after animation completes
            setTimeout(function () {
                toastEl.classList.remove("hiding");
                toast.dispose();
                toastEl.remove();
            }, 300);
        });
    });
});
