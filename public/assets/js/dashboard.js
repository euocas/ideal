        // ==========================================
        // BANNER / CARROSSEL — JS mínimo, sem libs
        // ==========================================
        (function () {
            const banner = document.getElementById('bannerCarrossel');
            if (!banner) return;

            const slides = banner.querySelectorAll('.banner-slide');
            const dots   = banner.querySelectorAll('.banner-dot');
            const prev   = banner.querySelector('[data-prev]');
            const next   = banner.querySelector('[data-next]');
            let index    = 0;
            let timer    = null;

            function goTo(i) {
                index = (i + slides.length) % slides.length;
                slides.forEach((s, idx) => s.classList.toggle('is-active', idx === index));
                dots.forEach((d, idx) => d.classList.toggle('is-active', idx === index));
            }

            function autoplay() {
                stop();
                timer = setInterval(() => goTo(index + 1), 6000);
            }

            function stop() {
                if (timer) clearInterval(timer);
            }

            prev.addEventListener('click', () => { goTo(index - 1); autoplay(); });
            next.addEventListener('click', () => { goTo(index + 1); autoplay(); });
            dots.forEach(d => d.addEventListener('click', () => {
                goTo(parseInt(d.dataset.goto, 10));
                autoplay();
            }));

            banner.addEventListener('mouseenter', stop);
            banner.addEventListener('mouseleave', autoplay);

            autoplay();
        })();
    
