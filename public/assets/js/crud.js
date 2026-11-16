'use strict';

/*
 * CRUD do site LINA-01.
 * O navegador nunca altera arrays locais como fonte principal: as operações
 * são enviadas à API PHP e os dados são carregados novamente do MySQL.
 */
const API_URL = './api/index.php';
let currentEntity = 'visitors';
let currentRecords = [];
let cachedVisitors = [];

const entityConfig = {
    visitors: {
        label: 'visitante',
        plural: 'visitantes',
        title: 'Novo Cadastro de Visitante',
        endpoint: 'visitors'
    },
    batteries: {
        label: 'teste',
        plural: 'baterias de teste',
        title: 'Registrar Bateria de Teste',
        endpoint: 'batteries'
    }
};

async function apiRequest(entity, method = 'GET', id = null, payload = null) {
    const url = new URL(API_URL, window.location.href);
    url.searchParams.set('entity', entity);
    if (id !== null) url.searchParams.set('id', String(id));

    const options = {
        method,
        headers: { 'Accept': 'application/json' }
    };
    if (payload !== null) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(payload);
    }

    const response = await fetch(url, options);
    let result;
    try {
        result = await response.json();
    } catch (error) {
        throw new Error('A resposta do servidor não está em JSON. Confira se o Apache e o PHP estão funcionando.');
    }

    if (!response.ok || !result.success) {
        throw new Error(result.message || 'Não foi possível concluir a operação.');
    }
    return result;
}

function showOperationPreview(method, entity, payload = null, id = null) {
    const preview = document.getElementById('sqlPreview');
    if (!preview) return;

    const operation = id ? `${method} ${entity}/${id}` : `${method} ${entity}`;
    const details = payload ? `\n${JSON.stringify(payload, null, 2)}` : '';
    preview.textContent = `${operation}${details}`;
}

async function loadVisitorOptions() {
    const select = document.getElementById('inputVisitor');
    if (!select) return;

    const result = await apiRequest('visitors');
    cachedVisitors = result.data || [];
    const selectedValue = select.value;
    select.replaceChildren(new Option('Sem visitante associado', ''));

    cachedVisitors.forEach(visitor => {
        select.add(new Option(`${visitor.name} — #${visitor.id}`, visitor.id));
    });
    if (selectedValue) select.value = selectedValue;
}

async function loadRecords() {
    const container = document.getElementById('recordsContainer');
    if (container) {
        container.innerHTML = '<div class="p-5 rounded-xl bg-paper border border-borderline text-sm text-muted">Carregando dados do banco de dados...</div>';
    }

    try {
        const [recordsResult, visitorsResult] = await Promise.all([
            apiRequest(currentEntity),
            apiRequest('visitors')
        ]);
        currentRecords = recordsResult.data || [];
        cachedVisitors = visitorsResult.data || [];
        populateVisitorSelect();
        renderRecords();
    } catch (error) {
        currentRecords = [];
        renderRecords(error.message);
        showToast(error.message, 'error');
    }
}

function populateVisitorSelect() {
    const select = document.getElementById('inputVisitor');
    if (!select) return;

    const selectedValue = select.value;
    select.replaceChildren(new Option('Sem visitante associado', ''));
    cachedVisitors.forEach(visitor => {
        select.add(new Option(`${visitor.name} — #${visitor.id}`, visitor.id));
    });
    select.value = selectedValue;
}

function createRecordCard(item) {
    const card = document.createElement('article');
    card.className = 'p-4 rounded-xl bg-paper border border-borderline flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-ink transition-all';

    const details = document.createElement('div');
    details.className = 'min-w-0';

    const marker = document.createElement('span');
    marker.className = 'font-mono text-[10px] text-terracotta uppercase font-semibold block';

    const title = document.createElement('h4');
    title.className = 'font-display font-bold text-sm text-ink break-words';

    const subtitle = document.createElement('span');
    subtitle.className = 'font-mono text-xs text-muted block mt-1 break-words';

    if (currentEntity === 'visitors') {
        marker.textContent = `ID: #${item.id} // VISITANTE`;
        title.textContent = item.name;
        subtitle.textContent = `${item.email} • ${item.interest}`;
    } else {
        marker.textContent = `TESTE #${item.id} // ${item.visitor_name ? `VISITANTE: ${item.visitor_name}` : 'SEM VISITANTE ASSOCIADO'}`;
        title.textContent = `Velocidade: ${Number(item.avg_speed).toFixed(2)} m/s | Tempo: ${Number(item.lap_time).toFixed(2)} s`;
        subtitle.textContent = `Status: ${item.status} • ${formatDate(item.created_at)}`;
    }

    details.append(marker, title, subtitle);

    const actions = document.createElement('div');
    actions.className = 'flex items-center gap-2 shrink-0';

    const editButton = document.createElement('button');
    editButton.type = 'button';
    editButton.className = 'px-2.5 py-1.5 rounded-md text-xs font-mono bg-surface border border-borderline hover:border-ink transition-colors';
    editButton.textContent = 'Editar';
    editButton.addEventListener('click', () => editRecord(Number(item.id)));

    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.className = 'px-2.5 py-1.5 rounded-md text-xs font-mono bg-surface border border-borderline text-terracotta hover:bg-terracotta hover:text-white transition-colors';
    deleteButton.textContent = 'Excluir';
    deleteButton.addEventListener('click', () => deleteRecord(Number(item.id)));

    actions.append(editButton, deleteButton);
    card.append(details, actions);
    return card;
}

function formatDate(value) {
    if (!value) return 'Data não informada';
    const parsed = new Date(String(value).replace(' ', 'T'));
    return Number.isNaN(parsed.getTime()) ? String(value) : parsed.toLocaleString('pt-BR');
}

function renderRecords(errorMessage = '') {
    const container = document.getElementById('recordsContainer');
    const recordCount = document.getElementById('recordCount');
    if (!container || !recordCount) return;

    const query = (document.getElementById('searchInput')?.value || '').trim().toLocaleLowerCase('pt-BR');
    const filtered = currentRecords.filter(item => {
        const searchable = currentEntity === 'visitors'
            ? `${item.id} ${item.name} ${item.email} ${item.interest}`
            : `${item.id} ${item.visitor_name || ''} ${item.avg_speed} ${item.lap_time} ${item.status}`;
        return searchable.toLocaleLowerCase('pt-BR').includes(query);
    });

    container.replaceChildren();
    recordCount.textContent = `${filtered.length} ${filtered.length === 1 ? 'registro encontrado' : 'registros encontrados'}`;

    if (errorMessage) {
        const error = document.createElement('div');
        error.className = 'p-5 rounded-xl bg-paper border border-borderline text-sm text-muted';
        error.textContent = errorMessage;
        container.append(error);
        return;
    }

    if (filtered.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'p-6 rounded-xl bg-paper border border-dashed border-borderline text-sm text-muted text-center';
        empty.textContent = query
            ? 'Nenhum registro corresponde à pesquisa.'
            : `Ainda não há ${entityConfig[currentEntity].plural} cadastrados. Use o formulário ao lado para criar o primeiro registro.`;
        container.append(empty);
        return;
    }

    filtered.forEach(item => container.append(createRecordCard(item)));
}

function switchEntity(entity) {
    if (!entityConfig[entity]) return;
    currentEntity = entity;
    resetCrudForm();

    const isVisitor = entity === 'visitors';
    const visitorButton = document.getElementById('entityBtn-visitors');
    const batteryButton = document.getElementById('entityBtn-batteries');
    const visitorFields = ['fieldGroup-name', 'fieldGroup-email', 'fieldGroup-interest'];
    const batteryFields = ['fieldGroup-extra'];

    visitorFields.forEach(id => document.getElementById(id)?.classList.toggle('hidden', !isVisitor));
    batteryFields.forEach(id => document.getElementById(id)?.classList.toggle('hidden', isVisitor));

    // Campos ocultos deixam de ser obrigatórios para que o navegador não bloqueie o outro formulário.
    document.getElementById('inputName').required = isVisitor;
    document.getElementById('inputEmail').required = isVisitor;
    document.getElementById('inputInterest').required = isVisitor;
    document.getElementById('inputTime').required = !isVisitor;
    document.getElementById('inputSpeed').required = !isVisitor;

    if (visitorButton && batteryButton) {
        visitorButton.className = isVisitor
            ? 'px-4 py-2 rounded-lg text-xs font-mono font-medium bg-ink text-paper transition-all'
            : 'px-4 py-2 rounded-lg text-xs font-mono font-medium text-muted hover:text-ink transition-all';
        batteryButton.className = !isVisitor
            ? 'px-4 py-2 rounded-lg text-xs font-mono font-medium bg-ink text-paper transition-all'
            : 'px-4 py-2 rounded-lg text-xs font-mono font-medium text-muted hover:text-ink transition-all';
    }

    document.getElementById('formTitle').textContent = entityConfig[entity].title;
    document.getElementById('tableTitle').textContent = isVisitor ? 'Visitantes cadastrados' : 'Baterias de teste registradas';
    document.getElementById('searchInput').value = '';
    loadRecords();
}
window.switchEntity = switchEntity;

function resetCrudForm() {
    const form = document.getElementById('crudForm');
    if (!form) return;
    form.reset();
    document.getElementById('editRecordId').value = '';
    document.getElementById('formModeBadge').textContent = 'Modo: Create (INSERT)';
    document.getElementById('submitBtn').textContent = 'Salvar Registro (CREATE)';
    document.getElementById('cancelEditBtn').classList.add('hidden');
    document.getElementById('formTitle').textContent = entityConfig[currentEntity].title;
}
window.resetCrudForm = resetCrudForm;

async function handleCrudSubmit(event) {
    event.preventDefault();

    const id = document.getElementById('editRecordId').value;
    const isUpdate = id !== '';
    const method = isUpdate ? 'PUT' : 'POST';
    const submitButton = document.getElementById('submitBtn');
    submitButton.disabled = true;
    submitButton.textContent = 'Salvando...';

    let payload;
    if (currentEntity === 'visitors') {
        payload = {
            name: document.getElementById('inputName').value.trim(),
            email: document.getElementById('inputEmail').value.trim(),
            interest: document.getElementById('inputInterest').value
        };
    } else {
        payload = {
            visitor_id: document.getElementById('inputVisitor').value || null,
            lap_time: document.getElementById('inputTime').value,
            avg_speed: document.getElementById('inputSpeed').value,
            status: document.getElementById('inputStatus').value
        };
    }

    try {
        await apiRequest(currentEntity, method, isUpdate ? id : null, payload);
        showOperationPreview(method, currentEntity, payload, isUpdate ? id : null);
        showToast(isUpdate ? 'Registro atualizado no MySQL.' : 'Registro cadastrado no MySQL.');
        resetCrudForm();
        await loadRecords();
    } catch (error) {
        showToast(error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = document.getElementById('editRecordId').value
            ? 'Atualizar Registro (UPDATE)'
            : 'Salvar Registro (CREATE)';
    }
}
window.handleCrudSubmit = handleCrudSubmit;

function editRecord(id) {
    const item = currentRecords.find(record => Number(record.id) === Number(id));
    if (!item) return;

    document.getElementById('editRecordId').value = String(item.id);
    document.getElementById('formModeBadge').textContent = `Modo: Update (EDIT #${id})`;
    document.getElementById('submitBtn').textContent = 'Atualizar Registro (UPDATE)';
    document.getElementById('cancelEditBtn').classList.remove('hidden');
    document.getElementById('formTitle').textContent = currentEntity === 'visitors'
        ? `Editar Visitante #${id}`
        : `Editar Bateria de Teste #${id}`;

    if (currentEntity === 'visitors') {
        document.getElementById('inputName').value = item.name || '';
        document.getElementById('inputEmail').value = item.email || '';
        document.getElementById('inputInterest').value = item.interest || '';
    } else {
        document.getElementById('inputTime').value = item.lap_time;
        document.getElementById('inputSpeed').value = item.avg_speed;
        document.getElementById('inputVisitor').value = item.visitor_id || '';
        document.getElementById('inputStatus').value = item.status || 'Concluído';
    }

    document.getElementById('crudForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
    showToast(`Registro #${id} carregado para edição.`, 'info');
}
window.editRecord = editRecord;

async function deleteRecord(id) {
    const label = currentEntity === 'visitors' ? 'visitante' : 'bateria de teste';
    const confirmation = window.confirm(`Deseja realmente excluir o registro #${id} (${label})?`);
    if (!confirmation) return;

    try {
        await apiRequest(currentEntity, 'DELETE', id);
        showOperationPreview('DELETE', currentEntity, null, id);
        showToast('Registro excluído do MySQL.');
        if (currentEntity === 'visitors') await loadVisitorOptions();
        await loadRecords();
    } catch (error) {
        showToast(error.message, 'error');
    }
}
window.deleteRecord = deleteRecord;

function filterRecords() {
    renderRecords();
}
window.filterRecords = filterRecords;

// Inicialização da listagem com dados persistidos no banco.
document.addEventListener('DOMContentLoaded', () => {
    switchEntity('visitors');
});
