<div x-data="importProgress()" x-init="initializeEcho()" id="progress-section" class="mt-4" x-show="isVisible" x-cloak>
    <h5 class="text-lg font-semibold text-blue-700 mb-4">Import Progress</h5>
    <div id="progress-container">
        <div class="progress-info mb-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-700" x-text="progress.message || 'Initializing...'"></span>
                <span class="text-gray-700 font-medium" x-text="progress.percentage + '%'"></span>
            </div>
            <div class="progress-stats mt-2">
                <small class="text-gray-500 text-sm">
                    Processed: <span class="font-medium" x-text="progress.processed"></span>/<span class="font-medium"
                        x-text="progress.total"></span> |
                    Successful: <span class="font-medium text-green-600" x-text="progress.successful"></span> |
                    Failed: <span class="font-medium text-red-600" x-text="progress.failed"></span>
                </small>
            </div>
        </div>
        <div class="progress mb-3 bg-gray-200 rounded-full h-5 overflow-hidden">
            <div class="progress-bar bg-blue-500 h-full transition-all duration-300 ease-out" role="progressbar"
                :style="`width: ${progress.percentage}%`" :aria-valuenow="progress.percentage" aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>
        <div class="text-center" x-show="!isCompleted">
            <div class="inline-flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"
                    role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <small class="text-gray-500 ml-2 text-sm" x-text="progress.message || 'Import in progress...'"></small>
            </div>
        </div>
        <div class="text-center" x-show="isCompleted">
            <div class="inline-flex items-center">
                <div class="rounded-full h-4 w-4 bg-green-500 flex items-center justify-center">
                    <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <small class="text-green-600 ml-2 text-sm font-medium">Import completed!</small>
            </div>
        </div>
    </div>
</div>

<script>
    function importProgress() {
        return {
            isVisible: false,
            isCompleted: false,
            progress: {
                percentage: 0,
                processed: 0,
                total: 0,
                successful: 0,
                failed: 0,
                message: 'Initializing...'
            },

            initializeEcho() {
                if (window.userId && window.Echo) {
                    window.Echo.private(`import-progress.${window.userId}`)
                        .listen('.progress.updated', (data) => this.updateProgress(data));
                }
            },

            updateProgress(data) {
                this.isVisible = true;
                this.progress = {...this.progress, ...data};

                if (data.percentage === 100 && data.message === "Import completed!") {
                    this.isCompleted = true;
                    this.showSuccessNotification();
                }
            },

            showSuccessNotification() {
                if (window.Notify) {
                    new Notify({
                        status: 'success',
                        title: 'Import successfully finished.',
                    });
                }
            }
        }
    }
</script>
