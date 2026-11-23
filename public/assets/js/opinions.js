'use strict';

const OPINIONS_API = './api/index.php?entity=opinions';
const OPINION_LABELS = {
    1: 'Achei que faltou coisa',
    2: 'Atendeu parcialmente às expectativas',
    3: 'Atendeu às expectativas',
    4: 'Superou as expectativas',
    5: 'Alcançou o propósito do projeto'
};

function formatOpinionDate(value) {
    if (!value) return '';
    const date = new Date(String(value).replace(' ', 'T'));
    if (Number.isNaN(date.getTime())) return '';
    return new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(date);
}

function renderOpinionComments(comments) {
    const container = document.getElementById('opinionComments');
    const totalElement = document.getElementById('opinionCommentsTotal');
    if (!container || !totalElement) return;

    const list = Array.isArray(comments) ? comments : [];
    totalElement.textContent = `${list.length} ${list.length === 1 ? 'comentário' : 'comentários'}`;
    container.replaceChildren();

    if (list.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'text-sm text-muted';
        empty.textContent = 'Ainda não há comentários. Seja a primeira pessoa a compartilhar sua opinião!';
        container.append(empty);
        return;
    }

    list.forEach((opinion) => {
        const card = document.createElement('article');
        card.className = 'rounded-xl border border-borderline bg-paper p-4';

        const header = document.createElement('div');
        header.className = 'flex flex-wrap items-start justify-between gap-2';

        const author = document.createElement('strong');
        author.className = 'text-sm font-semibold text-ink';
        author.textContent = opinion.name || 'Visitante';

        const level = document.createElement('span');
        level.className = 'text-xs font-mono text-terracotta';
        const numericLevel = Number(opinion.level);
        level.textContent = OPINION_LABELS[numericLevel] || 'Avaliação registrada';
        header.append(author, level);
        card.append(header);

        const comment = document.createElement('p');
        comment.className = 'mt-3 text-sm leading-relaxed text-muted whitespace-pre-wrap break-words';
        comment.textContent = opinion.comment || '';
        card.append(comment);

        const dateText = formatOpinionDate(opinion.created_at);
        if (dateText) {
            const date = document.createElement('p');
            date.className = 'mt-3 text-[11px] text-muted';
            date.textContent = dateText;
            card.append(date);
        }
        container.append(card);
    });
}

async function loadOpinionChart() {
    const chart = document.getElementById('opinionChart');
    const totalElement = document.getElementById('opinionTotal');
    if (!chart || !totalElement) return;
    try {
        const response = await fetch(OPINIONS_API, { headers: { Accept: 'application/json' } });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.message || 'Não foi possível carregar as avaliações.');
        const counts = result.data.counts || {};
        const total = Number(result.data.total || 0);
        totalElement.textContent = String(total);
        chart.replaceChildren();
        for (let level = 1; level <= 5; level++) {
            const count = Number(counts[level] || 0);
            const percent = total ? Math.round((count / total) * 100) : 0;
            const item = document.createElement('div');
            item.className = 'space-y-2';
            const header = document.createElement('div');
            header.className = 'flex items-start justify-between gap-3 text-sm';
            const label = document.createElement('span');
            label.className = 'text-ink font-medium';
            label.textContent = `${level}. ${OPINION_LABELS[level]}`;
            const value = document.createElement('span');
            value.className = 'font-mono text-xs text-muted whitespace-nowrap';
            value.textContent = `${count} (${percent}%)`;
            header.append(label, value);
            const track = document.createElement('div');
            track.className = 'h-3 rounded-full bg-paper border border-borderline overflow-hidden';
            const bar = document.createElement('div');
            bar.className = 'h-full rounded-full bg-terracotta transition-all duration-500';
            bar.style.width = `${percent}%`;
            track.append(bar);
            item.append(header, track);
            chart.append(item);
        }
        if (total === 0) {
            const empty = document.createElement('p');
            empty.className = 'text-sm text-muted mt-2';
            empty.textContent = 'Ainda não há avaliações. Seja a primeira pessoa a opinar!';
            chart.append(empty);
        }
        renderOpinionComments(result.data.comments || []);
    } catch (error) {
        chart.textContent = error.message;
        renderOpinionComments([]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('opinionForm');
    if (!form) return;
    loadOpinionChart();
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = document.getElementById('opinionSubmit');
        const feedback = document.getElementById('opinionFeedback');
        button.disabled = true;
        button.textContent = 'Enviando...';
        try {
            const response = await fetch(OPINIONS_API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    name: document.getElementById('opinionName').value.trim(),
                    level: Number(document.getElementById('opinionLevel').value),
                    comment: document.getElementById('opinionComment').value.trim()
                })
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Não foi possível enviar sua opinião.');
            feedback.textContent = result.message;
            feedback.className = 'text-xs text-green-700';
            form.reset();
            await loadOpinionChart();
        } catch (error) {
            feedback.textContent = error.message;
            feedback.className = 'text-xs text-terracotta';
        } finally {
            button.disabled = false;
            button.textContent = 'Enviar opinião';
        }
    });
});
