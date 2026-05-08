<style>
    /* ---- Slider only — no header overrides ---- */
    .slider-container {
        width: 100vw;
        height: 600px;
        overflow: hidden;
        position: relative;
        margin-left: calc(-50vw + 50%);
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    }

    .slider {
        display: flex;
        height: 100%;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform;
        transform: translate3d(0,0,0);
    }

    .slide {
        min-width: 100vw;
        height: 100%;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        backface-visibility: hidden;
        perspective: 1000px;
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 0;
        transition: opacity 1s ease, transform 0.8s ease-in-out;
        will-change: transform;
    }

    .slide img.loaded { opacity: 1; }

    .slide.active img { transform: scale(1.02); }

    .slide::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(0,0,0,0.2) 0%,
            rgba(0,0,0,0.1) 30%,
            rgba(0,0,0,0.6) 100%);
        z-index: 2;
    }

    .slide-content {
        position: absolute;
        bottom: 25%;
        left: 8%;
        max-width: 600px;
        color: #fff;
        z-index: 3;
        opacity: 0;
        transform: translateY(60px);
        transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.3s;
    }

    .slide.active .slide-content {
        opacity: 1;
        transform: translateY(0);
    }

    .slide-content h2 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .slide-content p {
        font-size: 1.3rem;
        color: #f8f9fa;
        margin-bottom: 30px;
        text-shadow: 1px 1px 6px rgba(0,0,0,0.8);
        line-height: 1.5;
        max-width: 500px;
    }

    .slide-content .btn {
        display: inline-block;
        background: linear-gradient(135deg, #1C6EA4, #154D71);
        color: #fff;
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        box-shadow: 0 6px 20px rgba(28,110,164,0.4);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .slide-content .btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .slide-content .btn:hover::before { left: 100%; }

    .slide-content .btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(28,110,164,0.6);
        border-color: rgba(255,255,255,0.3);
    }

    /* Arrows */
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.18);
        backdrop-filter: blur(15px);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.4s ease;
        z-index: 10;
        border: 2px solid rgba(255,255,255,0.35);
        box-shadow: 0 4px 18px rgba(0,0,0,0.22);
    }

    .slider-arrow:hover {
        background: rgba(255,255,255,0.35);
        transform: translateY(-50%) scale(1.12);
        box-shadow: 0 8px 28px rgba(0,0,0,0.35);
        border-color: rgba(255,255,255,0.6);
    }

    .arrow-img {
        width: 26px;
        height: 26px;
        object-fit: contain;
        filter: brightness(0) invert(1) drop-shadow(0 1px 3px rgba(0,0,0,0.4));
        transition: transform 0.3s ease;
    }

    .slider-arrow:hover .arrow-img { transform: scale(1.15); }

    .prev { left: 40px; }
    .next { right: 40px; }

    /* Dots */
    .slider-nav {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .nav-dot {
        height: 14px;
        width: 14px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        cursor: pointer;
        transition: all 0.4s ease;
        border: 2px solid rgba(255,255,255,0.6);
    }

    .nav-dot.active,
    .nav-dot:hover {
        background: #fff;
        transform: scale(1.3);
        box-shadow: 0 0 15px rgba(255,255,255,0.8);
    }

    /* Counter */
    .slide-counter {
        position: absolute;
        top: 30px;
        right: 30px;
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
        z-index: 10;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 25px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .slider-container { height: 500px; }
        .slide-content h2  { font-size: 2.8rem; }
        .slide-content     { left: 6%; max-width: 500px; }
    }

    @media (max-width: 768px) {
        .slider-container  { height: 450px; }
        .slide-content     { left: 5%; bottom: 20%; max-width: 90%; }
        .slide-content h2  { font-size: 2.2rem; }
        .slide-content p   { font-size: 1.1rem; }
        .slider-arrow      { width: 50px; height: 50px; }
        .prev              { left: 20px; }
        .next              { right: 20px; }
        .slide-counter     { top: 20px; right: 20px; font-size: 0.9rem; }
    }

    @media (max-width: 480px) {
        .slider-container      { height: 400px; }
        .slide-content h2      { font-size: 1.8rem; }
        .slide-content p       { font-size: 1rem; }
        .slide-content .btn    { padding: 12px 25px; font-size: 1rem; }
        .slider-arrow          { width: 45px; height: 45px; }
    }
</style>

<div class="slider-container">
    <div class="slider">
        <div class="slide active">
            <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=1200&h=600&fit=crop&crop=center" alt="Happy Dog">
            <div class="slide-content">
                <h2>Nutritious Pet Foods</h2>
                <p>Give your pets the best with our carefully selected, healthy and tasty food options for every breed and age.</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=1200&h=600&fit=crop&crop=center" alt="Playful Cat">
            <div class="slide-content">
                <h2>Fun &amp; Safe Pet Toys</h2>
                <p>Keep your pets active and happy with our wide range of safe, engaging, and durable toys.</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=1200&h=600&fit=crop&crop=center" alt="Cute Rabbit">
            <div class="slide-content">
                <h2>Stylish Pet Accessories</h2>
                <p>From collars to cozy beds, explore trendy and comfortable accessories that your pets will love.</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=1200&h=600&fit=crop&crop=center" alt="Pet Training">
            <div class="slide-content">
                <h2>Grooming Essentials</h2>
                <p>Pamper your pets with high-quality grooming tools and products for a clean, healthy look.</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=1200&h=600&fit=crop&crop=center" alt="Veterinary Care">
            <div class="slide-content">
                <h2>Travel &amp; Outdoor Gear</h2>
                <p>Take your pets on adventures with our reliable carriers, leashes, and outdoor essentials.</p>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </div>
    </div>

    <div class="slider-nav"></div>

    <div class="slider-arrow prev">
        <img src="../images/index/left-arrow.png" alt="Previous" class="arrow-img">
    </div>
    <div class="slider-arrow next">
        <img src="../images/index/right-arrow.png" alt="Next" class="arrow-img">
    </div>

    <div class="slide-counter">
        <span class="current-slide">1</span> / <span class="total-slides">5</span>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const slider       = document.querySelector(".slider");
        const slides       = document.querySelectorAll(".slide");
        const prevBtn      = document.querySelector(".prev");
        const nextBtn      = document.querySelector(".next");
        const navContainer = document.querySelector(".slider-nav");
        const currentSpan  = document.querySelector(".current-slide");
        const totalSpan    = document.querySelector(".total-slides");

        let current = 0;
        const count = slides.length;
        let interval, transitioning = false;

        function createDots() {
            for (let i = 0; i < count; i++) {
                const dot = document.createElement('div');
                dot.className = 'nav-dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => goTo(i));
                navContainer.appendChild(dot);
            }
        }

        function update() {
            if (transitioning) return;
            transitioning = true;
            slider.style.transform = `translateX(-${current * 100}%)`;
            slides.forEach((s, i) => s.classList.toggle('active', i === current));
            document.querySelectorAll('.nav-dot').forEach((d, i) => d.classList.toggle('active', i === current));
            currentSpan.textContent = current + 1;
            setTimeout(() => { transitioning = false; }, 800);
        }

        function next() { current = (current + 1) % count; update(); }
        function prev() { current = (current - 1 + count) % count; update(); }
        function goTo(i) { if (i !== current && !transitioning) { current = i; update(); reset(); } }

        function start() { interval = setInterval(next, 4000); }
        function stop()  { clearInterval(interval); }
        function reset() { stop(); start(); }

        nextBtn.addEventListener('click', () => { next(); reset(); });
        prevBtn.addEventListener('click', () => { prev(); reset(); });

        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft')  { prev(); reset(); }
            if (e.key === 'ArrowRight') { next(); reset(); }
        });

        let tx = 0, ty_end = 0;
        slider.addEventListener('touchstart', e => { tx = e.changedTouches[0].screenX; }, { passive: true });
        slider.addEventListener('touchend',   e => {
            const diff = tx - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); reset(); }
        }, { passive: true });

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());

        totalSpan.textContent = count;
        createDots();
        update();
        start();

        slides.forEach(s => {
            const img = s.querySelector('img');
            if (img.complete) img.classList.add('loaded');
            else img.onload = () => img.classList.add('loaded');
        });
    });
</script>
