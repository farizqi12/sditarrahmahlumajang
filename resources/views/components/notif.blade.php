            @if (session('success'))
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
                    <div class="toast align-items-center text-bg-success border-0 show" role="alert"
                        aria-live="assertive" aria-atomic="true" id="toast-success">
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
                    <div class="toast align-items-center text-bg-danger border-0 show" role="alert"
                        aria-live="assertive" aria-atomic="true" id="toast-error">
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
