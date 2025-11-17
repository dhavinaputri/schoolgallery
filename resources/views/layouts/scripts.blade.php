<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    // Show skeleton loading on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Show content after loading
        setTimeout(function() {
            document.querySelectorAll('.skeleton').forEach(function(el) {
                el.classList.remove('skeleton');
            });
            document.querySelectorAll('.skeleton-image').forEach(function(el) {
                el.classList.remove('skeleton-image');
            });
            document.querySelectorAll('.skeleton-text').forEach(function(el) {
                el.classList.remove('skeleton-text');
            });
            document.querySelectorAll('.content-loading').forEach(function(el) {
                el.classList.remove('opacity-0');
                el.classList.add('opacity-100');
            });
        }, 1000);
    });
    
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
    
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    }

    // Simple hover-intent for desktop dropdown (fallback for keyboard focus)
    const newsDropdownBtn = document.getElementById('newsDropdownBtn');
    const newsDropdownMenu = document.getElementById('newsDropdownMenu');
    if (newsDropdownBtn && newsDropdownMenu) {
        newsDropdownBtn.addEventListener('focus', () => newsDropdownMenu.classList.remove('invisible', 'opacity-0'));
        newsDropdownBtn.addEventListener('blur', () => newsDropdownMenu.classList.add('invisible', 'opacity-0'));
    }

    // Mobile news submenu toggle
    const mobileNewsToggle = document.getElementById('mobile-news-toggle');
    const mobileNewsSubmenu = document.getElementById('mobile-news-submenu');
    if (mobileNewsToggle && mobileNewsSubmenu) {
        mobileNewsToggle.addEventListener('click', () => {
            mobileNewsSubmenu.classList.toggle('hidden');
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Lazyload images
    document.addEventListener('DOMContentLoaded', function(){
        const imgs = document.querySelectorAll('img[data-src]');
        const observer = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    const img = entry.target; img.src = img.getAttribute('data-src'); img.removeAttribute('data-src'); observer.unobserve(img);
                }
            });
        }) : null;
        imgs.forEach(img => { if(observer){ observer.observe(img);} else { img.src = img.getAttribute('data-src'); img.removeAttribute('data-src'); } });
    });

    // Initialize Swiper if elements exist
    if (document.querySelector('.swiper-container')) {
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            }
        });
    }
    
    // Card shine effect on mousemove with 3D tilt
    const cards = document.querySelectorAll('.card-shine');
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const angleX = (y - centerY) / 25;
            const angleY = (centerX - x) / 25;
            
            // Apply 3D tilt effect
            card.style.transform = `perspective(1000px) rotateX(${-angleX}deg) rotateY(${angleY}deg) scale3d(1.02, 1.02, 1.02)`;
            
            const shineElement = card.querySelector('.card-shine-effect');
            if (shineElement) {
                shineElement.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 80%)`;
            }
        });
        
        card.addEventListener('mouseleave', function() {
            // Reset transform on mouse leave
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            
            const shineElement = card.querySelector('.card-shine-effect');
            if (shineElement) {
                shineElement.style.background = 'none';
            }
        });
    });
    
    // Parallax effect for hero section
    const parallaxContainer = document.getElementById('parallax-container');
    if (parallaxContainer) {
        const parallaxBg = document.querySelector('.parallax-bg');
        const parallaxElements = document.querySelectorAll('.parallax-element');
        
        window.addEventListener('mousemove', function(e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            if (parallaxBg) {
                const speedX = parallaxBg.getAttribute('data-speed') || 0.05;
                const x = mouseX * 100 * speedX;
                const y = mouseY * 100 * speedX;
                parallaxBg.style.transform = `translate(${-x}px, ${-y}px)`;
            }
            
            parallaxElements.forEach(element => {
                const speed = element.getAttribute('data-speed') || 0.1;
                const x = mouseX * 100 * speed;
                const y = mouseY * 100 * speed;
                element.style.transform = `translate(${-x}px, ${-y}px)`;
            });
        });
    }
</script>

<!-- Floating Chatbot Widget -->
<style>
    .sg-chatbot-button{position:fixed;right:20px;bottom:20px;z-index:9999;background:#2563eb;color:#fff;border:none;border-radius:9999px;width:56px;height:56px;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 15px -3px rgba(37,99,235,.3),0 4px 6px -2px rgba(37,99,235,.2);cursor:pointer}
    .sg-chatbot-panel{position:fixed;right:20px;bottom:90px;width:280px;max-width:92vw;height:400px;z-index:10000;background:#fff;border-radius:16px;box-shadow:0 20px 25px -5px rgba(0,0,0,.1),0 10px 10px -5px rgba(0,0,0,.04);display:none;flex-direction:column;overflow:hidden;border:1px solid #e5e7eb}
    .sg-chatbot-header{background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);color:#fff;padding:12px 14px;display:flex;align-items:center;justify-content:space-between}
    .sg-chatbot-messages{flex:1;padding:12px;overflow:auto;background:#f9fafb}
    .sg-chatbot-input{display:flex;gap:8px;padding:12px;border-top:1px solid #e5e7eb;background:#fff}
    .sg-chatbot-input input{flex:1;border:1px solid #d1d5db;border-radius:10px;padding:10px 12px;outline:none}
    .sg-chatbot-bubble{max-width:78%;margin:6px 0;padding:8px 10px;border-radius:12px;font-size:13px;line-height:1.4}
    .sg-chatbot-bubble.user{margin-left:auto;background:#e5edff;color:#1e3a8a}
    .sg-chatbot-bubble.bot{margin-right:auto;background:#fff;border:1px solid #e5e7eb;color:#111827}
    .sg-chatbot-row{display:flex;align-items:flex-start;gap:8px}
    .sg-avatar{flex:0 0 auto;width:28px;height:28px;border-radius:9999px;background:#2563eb;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700}
    .sg-content{white-space:pre-wrap}
    .sg-suggestions{display:block;white-space:nowrap;overflow-x:auto;-webkit-overflow-scrolling:touch;padding:10px 12px;border-top:1px solid #e5e7eb;background:#fff}
    .sg-chip{display:inline-block;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:9999px;padding:6px 10px;font-size:12px;color:#0f172a;cursor:pointer;margin-right:8px}
    .sg-chip:hover{background:#e2e8f0}
    .sg-chatbot-spinner{display:inline-block;width:18px;height:18px;border:2px solid rgba(37,99,235,.2);border-top-color:#2563eb;border-radius:50%;animation:sgspin 1s linear infinite}
    .sg-typing{display:inline-flex;align-items:center;gap:6px}
    .sg-dot{width:6px;height:6px;background:#6b7280;border-radius:50%;opacity:.5;animation:sgblink 1.2s infinite}
    .sg-dot:nth-child(2){animation-delay:.2s}
    .sg-dot:nth-child(3){animation-delay:.4s}
    @keyframes sgblink{0%,80%,100%{opacity:.2}40%{opacity:1}}
    @keyframes sgspin{to{transform:rotate(360deg)}}
    /* Improve scroll behavior */
    .sg-chatbot-messages{overscroll-behavior:contain}
    /* Keep suggestions visible at bottom */
    .sg-suggestions{position:sticky;bottom:0}

    /* Responsive tweaks */
    @media (max-width: 640px){
        .sg-chatbot-button{right:max(12px, env(safe-area-inset-right));bottom:max(12px, env(safe-area-inset-bottom))}
        .sg-chatbot-panel{left:max(12px, env(safe-area-inset-left));right:max(12px, env(safe-area-inset-right));width:auto;bottom:max(80px, calc(env(safe-area-inset-bottom) + 80px));height:min(70vh, 520px)}
        .sg-chatbot-input input{font-size:14px}
        .sg-chatbot-bubble{font-size:13px}
    }

    @media (min-width: 641px) and (max-width: 1024px){
        .sg-chatbot-panel{width:300px;height:420px}
    }
</style>
<button id="sg-chatbot-toggle" aria-label="Buka Eduspot" class="sg-chatbot-button">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>
</button>
<div id="sg-chatbot" class="sg-chatbot-panel">
    <div class="sg-chatbot-header">
        <div style="display:flex;align-items:center;gap:8px">
            <span style="display:inline-flex;width:26px;height:26px;border-radius:50%;background:#fff;color:#2563eb;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v4"/>
                    <rect x="3" y="7" width="18" height="10" rx="2"/>
                    <circle cx="8" cy="12" r="1"/>
                    <circle cx="16" cy="12" r="1"/>
                </svg>
            </span>
            <div>
                <div style="font-weight:700;font-size:14px">Eduspot</div>
                <div style="font-size:12px;opacity:.9">Asisten SMKN 4 Bogor & pendidikan</div>
            </div>
        </div>
        <button id="sg-chatbot-close" style="background:transparent;border:none;color:#fff;cursor:pointer">✕</button>
    </div>
    <div id="sg-chatbot-msgs" class="sg-chatbot-messages">
        <div class="sg-chatbot-bubble bot"><div class="sg-chatbot-row"><span class="sg-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4"/><rect x="3" y="7" width="18" height="10" rx="2"/><circle cx="8" cy="12" r="1"/><circle cx="16" cy="12" r="1"/></svg></span><div class="sg-content">Halo, saya Botspot! Tanya apa saja seputar SMKN 4 Bogor atau pendidikan ya 😊</div></div></div>
    </div>
    <div id="sg-chatbot-suggestions" class="sg-suggestions"></div>
    <form id="sg-chatbot-form" class="sg-chatbot-input">
        <input id="sg-chatbot-input" type="text" placeholder="Ketik pertanyaan Anda..." autocomplete="off" required />
        <button id="sg-chatbot-send" type="submit" class="btn-primary" style="border:none;border-radius:10px;padding:10px 14px;color:#fff;background:#2563eb;display:inline-flex;align-items:center;gap:8px"><span class="sg-send-label">Kirim</span><span class="sg-send-spinner" style="display:none;width:16px;height:16px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:sgspin 1s linear infinite"></span></button>
    </form>
    <template id="sg-msg-user"><div class="sg-chatbot-bubble user"><div class="sg-content"></div></div></template>
    <template id="sg-msg-bot"><div class="sg-chatbot-bubble bot"><div class="sg-chatbot-row"><span class="sg-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4"/><rect x="3" y="7" width="18" height="10" rx="2"/><circle cx="8" cy="12" r="1"/><circle cx="16" cy="12" r="1"/></svg></span><div class="sg-content"></div></div></div></template>
</div>
<script>
    (function(){
        const panel=document.getElementById('sg-chatbot');
        const toggle=document.getElementById('sg-chatbot-toggle');
        const closeBtn=document.getElementById('sg-chatbot-close');
        const form=document.getElementById('sg-chatbot-form');
        const input=document.getElementById('sg-chatbot-input');
        const msgs=document.getElementById('sg-chatbot-msgs');
        const tplUser=document.getElementById('sg-msg-user');
        const tplBot=document.getElementById('sg-msg-bot');
        const sugWrap=document.getElementById('sg-chatbot-suggestions');
        const sendBtn=document.getElementById('sg-chatbot-send');
        const sendLabel=sendBtn.querySelector('.sg-send-label');
        const sendSpinner=sendBtn.querySelector('.sg-send-spinner');
        let history=[];

        function scrollToBottom(){msgs.scrollTop=msgs.scrollHeight}
        function addMsg(role,text){
            const tpl=role==='user'?tplUser:tplBot; const el=tpl.content.firstElementChild.cloneNode(true); const content=el.querySelector('.sg-content'); if(content){ content.textContent=text; } else { el.textContent=text; } msgs.appendChild(el); scrollToBottom();
        }
        function setLoading(on){
            if(on){
                const wrap=document.createElement('div');
                wrap.className='sg-chatbot-bubble bot';
                wrap.id='sg-loading';
                wrap.innerHTML='<span class="sg-typing"><span class="sg-dot"></span><span class="sg-dot"></span><span class="sg-dot"></span></span>';
                msgs.appendChild(wrap);
                scrollToBottom();
            }
            else { const w=document.getElementById('sg-loading'); if(w) w.remove(); }
        }

        function setSending(on){
            sendBtn.disabled=!!on;
            sendSpinner.style.display=on?'inline-block':'none';
            sendLabel.textContent=on?'Mengirim...':'Kirim';
        }

        toggle.addEventListener('click',()=>{ panel.style.display = panel.style.display==='flex'?'none':'flex'; panel.style.display==='flex' && input.focus(); if(panel.style.display==='flex'){ panel.style.display='flex'; panel.style.flexDirection='column'; } });
        closeBtn.addEventListener('click',()=>{ panel.style.display='none'; });

        // Check if user is authenticated
        async function checkAuth() {
            try {
                const response = await fetch('{{ route('profile.edit') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                return response.ok;
            } catch (error) {
                console.error('Auth check failed:', error);
                return false;
            }
        }

        // Enter to send, Shift+Enter for newline
        input.addEventListener('keydown', (e)=>{
            if(e.key==='Enter' && !e.shiftKey){ e.preventDefault(); form.requestSubmit(); }
        });

        // Render quick suggestions
        const suggestions=['Jurusan di SMKN 4','Info Ekstrakurikuler','Alamat & Kontak Sekolah'];
        function renderSuggestions(){
            sugWrap.innerHTML='';
            suggestions.forEach((s)=>{
                const b=document.createElement('button'); b.type='button'; b.className='sg-chip'; b.textContent=s; b.addEventListener('click',()=>{ input.value=s; form.requestSubmit(); }); sugWrap.appendChild(b);
            });
        }
        renderSuggestions();

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Check authentication first
            const isAuthenticated = await checkAuth();
            if (!isAuthenticated) {
                addMsg('bot', 'Hai! Untuk menggunakan fitur chatbot, silakan login terlebih dahulu ya! 😊');
                return;
            }
            
            const text = input.value.trim();
            if (!text) return;
            
            addMsg('user', text);
            input.value = '';
            setLoading(true);
            setSending(true);
            
            try {
                const res = await fetch('{{ route('chatbot.ask') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({message: text, context: history})
                });
                
                if (res.status === 401) {
                    throw new Error('Unauthenticated');
                }
                
                const data = await res.json();
                setLoading(false);
                setSending(false);
                
                if (data && data.success) {
                    // Rapikan: hilangkan markdown tebal/miring sederhana
                    let answer = data.answer || '';
                    answer = answer.replace(/\*\*(.*?)\*\*/g,'$1').replace(/_(.*?)_/g,'$1');
                    addMsg('bot', answer);
                    history.push({role:'user',content:text});
                    history.push({role:'assistant',content:answer});
                }
                else{ addMsg('bot', (data && data.error) ? data.error : 'Maaf, terjadi kesalahan.'); }
            }catch(err){ setLoading(false); setSending(false); addMsg('bot','Tidak dapat terhubung ke server.'); }
        });
    })();
</script>

@stack('scripts')