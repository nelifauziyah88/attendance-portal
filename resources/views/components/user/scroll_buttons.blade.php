<div class="fixed bottom-5 right-5 z-40 flex flex-col gap-2 sm:bottom-8 sm:right-8">
    <button type="button" id="scroll-top-button" aria-label="Scroll to top"
        class="pointer-events-none flex h-11 w-11 items-center justify-center rounded-full bg-[#6b2dc8] text-white opacity-0 shadow-lg shadow-blue-200/60 transition duration-300 hover:scale-105 hover:bg-[#2a52d9] focus:outline-none focus:ring-4 focus:ring-blue-200">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <button type="button" id="scroll-bottom-button" aria-label="Scroll to bottom"
        class="pointer-events-none flex h-11 w-11 items-center justify-center rounded-full bg-[#6b2dc8] text-white opacity-0 shadow-lg shadow-blue-200/60 transition duration-300 hover:scale-105 hover:bg-[#2a52d9] focus:outline-none focus:ring-4 focus:ring-blue-200">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
</div>

<script>
    (function() {
        const topButton = document.getElementById('scroll-top-button');
        const bottomButton = document.getElementById('scroll-bottom-button');

        function setVisible(button, visible) {
            button.classList.toggle('opacity-0', !visible);
            button.classList.toggle('pointer-events-none', !visible);
        }

        function update() {
            const position = window.scrollY;
            const maxScroll = document.documentElement.scrollHeight - window.innerHeight;

            setVisible(topButton, position > 80);
            setVisible(bottomButton, position < maxScroll - 80);
        }

        topButton.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        bottomButton.addEventListener('click', function() {
            window.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' });
        });

        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        window.addEventListener('load', update);

        if ('ResizeObserver' in window) {
            new ResizeObserver(update).observe(document.body);
        }

        update();
    })();
</script>