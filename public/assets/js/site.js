'use strict';

/* -----------------------------------------------------------
   Interações visuais do site: notificações, navegação e pista.
   A simulação do canvas é ilustrativa; não controla o ESP32.
----------------------------------------------------------- */

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto px-4 py-3 rounded-xl border text-sm font-medium shadow-lg transition-all transform translate-y-2 opacity-0 flex items-center gap-3 ${
        type === 'success' ? 'bg-ink text-paper border-ink' : 'bg-surface text-ink border-borderline'
    }`;
    // textContent evita interpretar mensagens como HTML.
    toast.textContent = String(message);
    container.appendChild(toast);

    requestAnimationFrame(() => toast.classList.remove('translate-y-2', 'opacity-0'));
    window.setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
        window.setTimeout(() => toast.remove(), 350);
    }, 3500);
}

async function copyToClipboard(elementId) {
    const input = document.getElementById(elementId);
    if (!input) return;

    try {
        await navigator.clipboard.writeText(input.value);
    } catch (error) {
        input.focus();
        input.select();
        document.execCommand('copy');
    }
    showToast('Link copiado para a área de transferência.');
}
window.copyToClipboard = copyToClipboard;

// Menu compacto para telas menores.
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');
if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden');
        mobileMenuBtn.setAttribute('aria-expanded', String(!isOpen));
    });
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
    });
}

// Efeitos de rolagem: progresso, sombra do cabeçalho e entrada escalonada dos cartões.
const pageHeader = document.querySelector('header');
const progressTrack = document.createElement('div');
progressTrack.className = 'scroll-progress';
progressTrack.setAttribute('aria-hidden', 'true');
progressTrack.innerHTML = '<span></span>';
document.body.appendChild(progressTrack);
const progressFill = progressTrack.firstElementChild;
let scrollTicking = false;
function updateScrollEffects() {
    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollable > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollable)) : 0;
    progressFill.style.transform = `scaleX(${progress})`;
    pageHeader?.classList.toggle('is-scrolled', window.scrollY > 12);
    scrollTicking = false;
}
window.addEventListener('scroll', () => {
    if (!scrollTicking) {
        window.requestAnimationFrame(updateScrollEffects);
        scrollTicking = true;
    }
}, { passive: true });
updateScrollEffects();

// Adiciona movimento alternado aos blocos e cartões ao entrarem na área visível.
const animatedSections = document.querySelectorAll('#circuito, #arquitetura, #opiniao-projeto, #modelagem, #entregaveis');
animatedSections.forEach(section => {
    section.querySelectorAll('figure, .grid > div, .grid > article, .rounded-2xl').forEach(card => {
        if (!card.classList.contains('reveal') && !card.closest('.reveal')) card.classList.add('reveal');
    });
});
const motionTargets = document.querySelectorAll(
    '#circuito .reveal, #arquitetura .reveal, #opiniao-projeto .reveal, #modelagem .reveal, #entregaveis .reveal'
);
motionTargets.forEach((element, index) => {
    if (index % 4 === 1) element.classList.add('from-left');
    else if (index % 4 === 2) element.classList.add('from-right');
    else if (index % 4 === 3) element.classList.add('zoom-in');
});

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                currentObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -55px 0px' });
    document.querySelectorAll('.reveal:not(.active)').forEach(element => observer.observe(element));
} else {
    document.querySelectorAll('.reveal').forEach(element => element.classList.add('active'));
}

// Abas demonstrativas da arquitetura PHP.
function switchArchTab(tabName) {
    const availableTabs = ['service', 'repo', 'db'];
    if (!availableTabs.includes(tabName)) return;

    availableTabs.forEach(tab => {
        const panel = document.getElementById(`archTab-${tab}`);
        const button = document.getElementById(`tabBtn-${tab}`);
        if (!panel || !button) return;

        panel.classList.toggle('hidden', tab !== tabName);
        button.className = tab === tabName
            ? 'w-full text-left p-4 rounded-xl border border-ink bg-ink text-paper transition-all flex items-center justify-between group'
            : 'w-full text-left p-4 rounded-xl border border-borderline bg-paper text-ink hover:border-ink transition-all flex items-center justify-between group';
    });
}
window.switchArchTab = switchArchTab;

/* -----------------------------------------------------------
   Simulador visual LINA-01. Ele anima um desenho na tela para
   explicar a ideia de seguir uma linha; não é telemetria real.
----------------------------------------------------------- */
const canvas = document.getElementById('robotCanvas');
const wrapper = document.getElementById('trackCanvasWrapper');
if (canvas && wrapper) {
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let trackPoints = [];
    let robotPos = { x: 50, y: 150, angle: 0 };
    let simRunning = true;
    let robotIndex = 0;
    let animationFrame = null;
    let previousFrameTime = 0;

    function resizeCanvas() {
        const oldWidth = canvas.width || wrapper.clientWidth;
        const oldHeight = canvas.height || wrapper.clientHeight;
        const newWidth = Math.max(1, wrapper.clientWidth);
        const newHeight = Math.max(1, wrapper.clientHeight);
        canvas.width = newWidth;
        canvas.height = newHeight;

        if (trackPoints.length > 0 && oldWidth > 0 && oldHeight > 0) {
            const scaleX = newWidth / oldWidth;
            const scaleY = newHeight / oldHeight;
            trackPoints = trackPoints.map(point => ({ x: point.x * scaleX, y: point.y * scaleY }));
            robotPos.x *= scaleX;
            robotPos.y *= scaleY;
        } else {
            loadPresetTrack(1);
        }
    }

    function loadPresetTrack(presetId) {
        trackPoints = [];
        robotIndex = 0;
        const width = canvas.width || 400;
        const height = canvas.height || 300;

        if (presetId === 1) {
            // Circuito em S.
            for (let x = 40; x < width - 40; x += 2) {
                const y = height / 2 + Math.sin(x * 0.02) * (height * 0.3);
                trackPoints.push({ x, y });
            }
        } else {
            // Oval estilizado.
            const cx = width / 2;
            const cy = height / 2;
            const rx = width * 0.38;
            const ry = height * 0.35;
            for (let angle = 0; angle < Math.PI * 2; angle += 0.02) {
                trackPoints.push({
                    x: cx + rx * Math.cos(angle),
                    y: cy + ry * Math.sin(angle)
                });
            }
        }

        if (trackPoints.length) {
            robotPos = { ...trackPoints[0], angle: 0 };
        }
    }

    function addPointFromEvent(event) {
        const rect = canvas.getBoundingClientRect();
        trackPoints.push({ x: event.clientX - rect.left, y: event.clientY - rect.top });
    }

    document.getElementById('btnTrackPreset1')?.addEventListener('click', () => loadPresetTrack(1));
    document.getElementById('btnTrackPreset2')?.addEventListener('click', () => loadPresetTrack(2));
    document.getElementById('btnClearTrack')?.addEventListener('click', () => {
        trackPoints = [];
        robotIndex = 0;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        updateSensorIndicators([false, false]);
        showToast('Pista limpa. Desenhe um novo caminho no espaço.');
    });

    canvas.addEventListener('pointerdown', event => {
        isDrawing = true;
        trackPoints = [];
        robotIndex = 0;
        canvas.setPointerCapture?.(event.pointerId);
        addPointFromEvent(event);
    });
    canvas.addEventListener('pointermove', event => {
        if (isDrawing) addPointFromEvent(event);
    });
    canvas.addEventListener('pointerup', () => { isDrawing = false; });
    canvas.addEventListener('pointercancel', () => { isDrawing = false; });

    function updateSensorIndicators(states) {
        document.querySelectorAll('.sensor-box').forEach((box, index) => {
            const active = Boolean(states[index]);
            box.textContent = active ? '1' : '0';
            box.className = active
                ? 'w-8 h-5 rounded bg-ink text-paper flex items-center justify-center font-mono text-[10px] font-bold sensor-box sensor-active'
                : 'w-8 h-5 rounded bg-paper border border-borderline flex items-center justify-center font-mono text-[10px] text-muted sensor-box';
        });

        const motorValue = document.getElementById('motorValue');
        if (motorValue) {
            const [left, right] = states;
            let response = 'Esq.: normal | Dir.: normal';
            if (left && !right) response = 'Esq.: reduzir | Dir.: avançar';
            else if (!left && right) response = 'Esq.: avançar | Dir.: reduzir';
            else if (!left && !right) response = 'Linha não detectada';
            motorValue.textContent = response;
        }
    }

    function distanceToTrack(point) {
        let minimum = Infinity;
        for (const trackPoint of trackPoints) {
            const distance = Math.hypot(point.x - trackPoint.x, point.y - trackPoint.y);
            if (distance < minimum) minimum = distance;
        }
        return minimum;
    }

    function readVirtualSensors() {
        if (trackPoints.length < 2) return [false, false];
        const forward = { x: Math.cos(robotPos.angle), y: Math.sin(robotPos.angle) };
        const side = { x: -forward.y, y: forward.x };
        const sensorForward = 10;
        const sensorSpacing = 7;
        const threshold = 8;
        const leftSensor = {
            x: robotPos.x + forward.x * sensorForward + side.x * sensorSpacing,
            y: robotPos.y + forward.y * sensorForward + side.y * sensorSpacing
        };
        const rightSensor = {
            x: robotPos.x + forward.x * sensorForward - side.x * sensorSpacing,
            y: robotPos.y + forward.y * sensorForward - side.y * sensorSpacing
        };
        return [distanceToTrack(leftSensor) <= threshold, distanceToTrack(rightSensor) <= threshold];
    }

    function drawRobot() {
        ctx.save();
        ctx.translate(robotPos.x, robotPos.y);
        ctx.rotate(robotPos.angle);

        // Chassi e rodas do desenho demonstrativo.
        ctx.fillStyle = '#C85A32';
        if (typeof ctx.roundRect === 'function') {
            ctx.beginPath();
            ctx.roundRect(-14, -10, 28, 20, 5);
            ctx.fill();
        } else {
            ctx.fillRect(-14, -10, 28, 20);
        }
        ctx.fillStyle = '#141414';
        ctx.fillRect(-10, -14, 8, 4);
        ctx.fillRect(-10, 10, 8, 4);
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(8, -6, 4, 12);
        ctx.fillStyle = '#141414';
        ctx.beginPath(); ctx.arc(12, -7, 2.2, 0, Math.PI * 2); ctx.fill();
        ctx.beginPath(); ctx.arc(12, 7, 2.2, 0, Math.PI * 2); ctx.fill();
        ctx.restore();
    }

    function animateRobot(timestamp = 0) {
        const delta = previousFrameTime ? Math.min(timestamp - previousFrameTime, 40) : 16.67;
        previousFrameTime = timestamp;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (trackPoints.length > 1) {
            ctx.beginPath();
            ctx.moveTo(trackPoints[0].x, trackPoints[0].y);
            for (let index = 1; index < trackPoints.length; index++) {
                ctx.lineTo(trackPoints[index].x, trackPoints[index].y);
            }
            ctx.strokeStyle = '#141414';
            ctx.lineWidth = 10;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.stroke();

            if (simRunning) {
                robotIndex = (robotIndex + delta * 0.08) % trackPoints.length;
                const current = trackPoints[Math.floor(robotIndex)];
                const next = trackPoints[(Math.floor(robotIndex) + 5) % trackPoints.length];
                if (current && next) {
                    robotPos.x = current.x;
                    robotPos.y = current.y;
                    robotPos.angle = Math.atan2(next.y - current.y, next.x - current.x);
                }
            }
            drawRobot();

            // Leitura virtual: compara a posição dos dois sensores com o traçado desenhado.
            updateSensorIndicators(readVirtualSensors());
        }

        animationFrame = requestAnimationFrame(animateRobot);
    }

    const toggleButton = document.getElementById('btnToggleSim');
    toggleButton?.addEventListener('click', () => {
        simRunning = !simRunning;
        toggleButton.textContent = simRunning ? 'Pausar Robô' : 'Retomar Robô';
    });

    window.addEventListener('resize', resizeCanvas);
    if ('ResizeObserver' in window) {
        new ResizeObserver(resizeCanvas).observe(wrapper);
    }

    resizeCanvas();
    animationFrame = requestAnimationFrame(animateRobot);
    window.addEventListener('pagehide', () => {
        if (animationFrame !== null) cancelAnimationFrame(animationFrame);
    }, { once: true });
}
