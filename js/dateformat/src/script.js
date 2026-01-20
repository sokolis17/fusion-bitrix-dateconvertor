(function() {
    'use strict';

    // Получаем конфигурацию
    const config = window.SmartdateDateConverterConfig || {};
    
    if (config.dateFormat !== 'datetime') {
        return; // Если формат не datetime, ничего не делаем
    }

    /**
     * Форматирует timestamp в формат дд.мм.гггг чч:мм
     */
    function formatDateTime(timestamp) {
        const date = new Date(timestamp * 1000);
        
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${day}.${month}.${year} ${hours}:${minutes}`;
    }

    /**
     * Извлекает timestamp из data-атрибута элемента
     */
    function getTimestamp(element) {
        // Ищем timestamp в различных data-атрибутах
        const timestamp = element.getAttribute('data-timestamp') ||
                         element.getAttribute('data-time') ||
                         element.getAttribute('data-created');
        
        if (timestamp) {
            return parseInt(timestamp, 10);
        }

        // Пытаемся получить из родительского элемента
        const parent = element.closest('[data-timestamp], [data-time], [data-created]');
        if (parent) {
            return parseInt(
                parent.getAttribute('data-timestamp') ||
                parent.getAttribute('data-time') ||
                parent.getAttribute('data-created'),
                10
            );
        }

        return null;
    }

    /**
     * Заменяет относительное время на абсолютное
     */
    function replaceRelativeTime(element) {
        const timestamp = getTimestamp(element);
        
        if (!timestamp) {
            return;
        }

        const formattedDate = formatDateTime(timestamp);
        
        // Заменяем текст
        if (element.textContent && element.textContent.trim()) {
            element.textContent = formattedDate;
            element.setAttribute('title', formattedDate);
        }
    }

    /**
     * Обработка элементов с датами создания
     */
    function processDateElements() {
        // Селекторы для поиска элементов с датами
        const selectors = [
            // Задачи - канбан и список
            '.task-kanban-item-created',
            '.tasks-grid-created',
            '.tasks-kanban-item .tasks-kanban-item-date',
            
            // CRM - канбан
            '.crm-kanban-item-created',
            '.crm-kanban-item-date',
            
            // CRM - список (лиды, сделки, контакты, компании)
            '.crm-list-item-created',
            '.main-grid-cell-content[data-timestamp]',
            
            // Общие селекторы для дат
            '.feed-post-date',
            '.task-detail-created-label',
            '.crm-entity-widget-content-block-date',
            
            // Битрикс24 относительное время
            '.ui-relative-time',
            '[data-timestamp]'
        ];

        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                if (!el.hasAttribute('data-smartdate-processed')) {
                    replaceRelativeTime(el);
                    el.setAttribute('data-smartdate-processed', 'true');
                }
            });
        });
    }

    /**
     * Наблюдатель за изменениями DOM
     */
    function initMutationObserver() {
        const observer = new MutationObserver(mutations => {
            let shouldProcess = false;
            
            mutations.forEach(mutation => {
                if (mutation.addedNodes.length > 0) {
                    shouldProcess = true;
                }
            });
            
            if (shouldProcess) {
                processDateElements();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Инициализация
     */
    function init() {
        // Обрабатываем существующие элементы
        processDateElements();
        
        // Запускаем наблюдатель
        initMutationObserver();
        
        // Дополнительная обработка через небольшой интервал
        // для страниц с отложенной загрузкой контента
        setTimeout(() => processDateElements(), 1000);
        setTimeout(() => processDateElements(), 3000);
    }

    // Запуск после загрузки DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Также запускаем при событиях Битрикс24
    if (window.BX) {
        BX.ready(init);
        
        // Обработка при изменении канбана
        BX.addCustomEvent('Kanban.Grid:onItemMoved', () => {
            setTimeout(processDateElements, 100);
        });
        
        // Обработка при загрузке новых элементов
        BX.addCustomEvent('Kanban.Grid:render', () => {
            setTimeout(processDateElements, 100);
        });
        
        // Обработка основной сетки
        BX.addCustomEvent('Grid::updated', () => {
            setTimeout(processDateElements, 100);
        });
    }
})();