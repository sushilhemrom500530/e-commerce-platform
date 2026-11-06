<!-- Fixed Top-Right Toast Notifications Container -->
<div id="toast-container" class="fixed top-5 right-5 sm:top-6 sm:right-6 z-[99999] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    @if(session('success'))
        <div class="toast-item pointer-events-auto transform translate-x-0 opacity-100 transition-all duration-300 ease-out flex items-start gap-3 p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-emerald-100 shadow-xl shadow-slate-900/10" role="alert" data-autodismiss="4500">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-bold text-slate-900">Success</p>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-item pointer-events-auto transform translate-x-0 opacity-100 transition-all duration-300 ease-out flex items-start gap-3 p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-rose-100 shadow-xl shadow-slate-900/10" role="alert" data-autodismiss="5000">
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-bold text-slate-900">Error</p>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('status'))
        <div class="toast-item pointer-events-auto transform translate-x-0 opacity-100 transition-all duration-300 ease-out flex items-start gap-3 p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-indigo-100 shadow-xl shadow-slate-900/10" role="alert" data-autodismiss="4500">
            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-bold text-slate-900">Notice</p>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('status') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif
</div>

<script>
    function dismissToast(element) {
        if (!element) return;
        element.classList.remove('translate-x-0', 'opacity-100');
        element.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (element && element.parentNode) {
                element.remove();
            }
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('#toast-container .toast-item');
        toasts.forEach(toast => {
            const timeout = parseInt(toast.getAttribute('data-autodismiss') || '4500', 10);
            setTimeout(() => {
                dismissToast(toast);
            }, timeout);
        });
    });

    window.showToast = function(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const isSuccess = type === 'success';
        const isError = type === 'error';
        const title = isSuccess ? 'Success' : (isError ? 'Error' : 'Notice');
        const borderColor = isSuccess ? 'border-emerald-100' : (isError ? 'border-rose-100' : 'border-indigo-100');
        const badgeBg = isSuccess ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : (isError ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100');
        const iconSvg = isSuccess 
            ? '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'
            : (isError 
                ? '<svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>'
                : '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>');

        const toast = document.createElement('div');
        toast.className = `toast-item pointer-events-auto transform translate-x-full opacity-0 transition-all duration-300 ease-out flex items-start gap-3 p-4 rounded-2xl bg-white/95 backdrop-blur-md border ${borderColor} shadow-xl shadow-slate-900/10`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="w-9 h-9 rounded-xl ${badgeBg} border flex items-center justify-center shrink-0">
                ${iconSvg}
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-bold text-slate-900">${title}</p>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">${message}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer shrink-0" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });

        setTimeout(() => {
            dismissToast(toast);
        }, 4500);
    };
</script>
