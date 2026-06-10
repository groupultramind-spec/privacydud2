// Executar imediatamente a lógica principal (sem DOMContentLoaded pois o base64 document.write já passou dessa fase)
(function initApp() {
    let promoBanner = document.getElementById("promoBanner");
    if (promoBanner) {
        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        promoBanner.innerText = `ESSA PROMOÇÃO É VÁLIDA ATÉ ${day}/${month}/${year}`;
    }

    const tabs = document.querySelectorAll('.tab');
    const postsContent = document.getElementById('posts-content');
    const mediaContent = document.getElementById('media-content');
    const spinnerWrap = document.getElementById('tab-spinner-wrap');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            if (postsContent) postsContent.style.display = 'none';
            if (mediaContent) mediaContent.style.display = 'none';
            if (spinnerWrap) spinnerWrap.style.display = 'flex';
            
            setTimeout(() => {
                if (spinnerWrap) spinnerWrap.style.display = 'none';
                const target = tab.getAttribute('data-tab');
                if (target === 'posts' && postsContent) {
                    postsContent.style.display = 'block';
                } else if (target === 'media' && mediaContent) {
                    mediaContent.style.display = 'block';
                }
            }, 600);
        });
    });

    const promotionsHeader = document.querySelector('.promotions-header');
    const promotions = document.querySelector('.promotions');
    if (promotionsHeader && promotions) {
        promotionsHeader.addEventListener('click', () => {
            promotions.classList.toggle('active');
        });
    }
})();

function abrirModal(planoId, nome, preco, bonus) {
    // Redirecionamento direto para o sistema de pagamento
    window.location.href = `checkout.php?plan=${planoId}&price=${preco}`;
}

function handleLike(btn) {
    let start = parseInt(btn.getAttribute('data-start'));
    let countSpan = btn.querySelector('.like-count');
    if (!btn.classList.contains('liked')) {
        btn.classList.add('liked');
        btn.style.color = '#ef4444';
        btn.querySelector('svg').style.fill = '#ef4444';
        countSpan.innerText = start + 1;
        
        // Open the unlock video modal after a short delay
        setTimeout(() => {
            abrirUnlockModal();
        }, 300);
    } else {
        btn.classList.remove('liked');
        btn.style.color = 'var(--gray-500)';
        btn.querySelector('svg').style.fill = 'none';
        countSpan.innerText = start;
    }
}

function abrirUnlockModal() {
    const modal = document.getElementById('unlock-modal');
    if (modal) {
        const btn = document.getElementById('unlock-btn');
        if (btn && typeof _P !== 'undefined' && _P.pacote_video) {
            const fmt = 'R$ ' + Number(_P.pacote_video.preco).toFixed(2).replace('.', ',');
            btn.textContent = `DESBLOQUEAR CONTEÚDO - ${fmt}`;
            btn.setAttribute('onclick', `abrirModal('pacote_video', '${_P.pacote_video.nome}', '${_P.pacote_video.preco}', false); return false;`);
        }
        modal.classList.add('active');
    }
}

function fecharUnlockModal() {
    const modal = document.getElementById('unlock-modal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function animarEAssinar(btn) {
    btn.style.transition = "transform 0.4s, color 0.4s";
    btn.style.transform = 'scale(1.3)';
    btn.style.color = '#f97316';
    setTimeout(() => {
        btn.style.transform = 'scale(1)';
        btn.style.color = 'var(--gray-500)';
        setTimeout(() => {
            if(typeof _P !== 'undefined') {
                abrirModal('mensal', _P.mensal.nome, _P.mensal.preco, _P.mensal.bonus);
            }
        }, 100);
    }, 400);
}

// Lógica de rotação do carrossel de mídias
(function initMiniCarousels() {
    setInterval(() => {
        const carousels = document.querySelectorAll('.mini-carousel');
        carousels.forEach(carousel => {
            const items = carousel.querySelectorAll('.mini-carousel-item');
            if (items.length <= 1) return;
            let activeIdx = -1;
            items.forEach((item, index) => {
                if (item.classList.contains('active')) {
                    activeIdx = index;
                }
            });
            if (activeIdx !== -1) {
                items[activeIdx].classList.remove('active');
                const nextIdx = (activeIdx + 1) % items.length;
                items[nextIdx].classList.add('active');
            }
        });
    }, 2500);
})();
