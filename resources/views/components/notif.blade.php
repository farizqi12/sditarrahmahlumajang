       <style>
           /* Animation for toast entrance */
           @keyframes toastSlideIn {
               from {
                   transform: translateX(100%);
                   opacity: 0;
               }

               to {
                   transform: translateX(0);
                   opacity: 1;
               }
           }

           /* Animation for toast exit */
           @keyframes toastSlideOut {
               from {
                   transform: translateX(0);
                   opacity: 1;
               }

               to {
                   transform: translateX(100%);
                   opacity: 0;
               }
           }

           /* Apply animations to toast */
           .toast.show {
               animation: toastSlideIn 0.3s forwards;
           }

           .toast.hiding {
               animation: toastSlideOut 0.3s forwards;
           }
       </style>

       @if (session('success'))
           <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
               <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                   aria-atomic="true" id="toast-success">
                   <div class="d-flex">
                       <div class="toast-body">
                           {{ session('success') }}
                       </div>
                       <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                           aria-label="Close"></button>
                   </div>
               </div>
           </div>
       @endif

       @if (session('error'))
           <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
               <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                   aria-atomic="true" id="toast-error">
                   <div class="d-flex">
                       <div class="toast-body">
                           {{ session('error') }}
                       </div>
                       <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                           aria-label="Close"></button>
                   </div>
               </div>
           </div>
       @endif

       <script>
           document.addEventListener("DOMContentLoaded", function() {
               var toastElList = [].slice.call(document.querySelectorAll(".toast"));

               toastElList.forEach(function(toastEl) {
                   // Initialize toast with 4 seconds delay
                   var toast = new bootstrap.Toast(toastEl, {
                       delay: 4000,
                       animation: false, // We'll handle animation manually
                   });

                   // Show toast with animation
                   toastEl.classList.add("show");
                   toast.show();

                   // Handle hiding animation
                   toastEl.addEventListener("hide.bs.toast", function() {
                       toastEl.classList.remove("show");
                       toastEl.classList.add("hiding");

                       // Remove element after animation completes
                       setTimeout(function() {
                           toastEl.classList.remove("hiding");
                           toast.dispose();
                           toastEl.remove();
                       }, 300);
                   });
               });
           });
       </script>
