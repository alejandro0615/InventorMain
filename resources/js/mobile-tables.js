document.addEventListener('DOMContentLoaded', () => {
    const MOBILE_BREAKPOINT = 768; // px
    const MOBILE_PAGE_SIZE = 5; // per requirements

    function createPaginator(totalPages, currentPage, container, onPage) {
        container.innerHTML = '';
        const paginator = document.createElement('div');
        paginator.className = 'mobile-paginator';

        for (let p = 1; p <= totalPages; p++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = p === currentPage ? 'page-btn active' : 'page-btn';
            btn.textContent = p;
            btn.addEventListener('click', () => onPage(p));
            paginator.appendChild(btn);
        }

        container.appendChild(paginator);
    }

    function buildMobileListFromTable(table) {
        const thead = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        const tbodyRows = Array.from(table.querySelectorAll('tbody tr'));
        if (tbodyRows.length === 0) return;

        // Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'mobile-table-list';
        const listContainer = document.createElement('div');
        listContainer.className = 'mobile-list-container';
        const pagerContainer = document.createElement('div');
        pagerContainer.className = 'mobile-pager-container';

        wrapper.appendChild(listContainer);
        wrapper.appendChild(pagerContainer);

        // Build pages
        const pages = [];
        for (let i = 0; i < tbodyRows.length; i += MOBILE_PAGE_SIZE) {
            pages.push(tbodyRows.slice(i, i + MOBILE_PAGE_SIZE));
        }

        function renderPage(pageIndex) {
            listContainer.innerHTML = '';
            const rows = pages[pageIndex];
            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const card = document.createElement('div');
                card.className = 'mobile-card card';

                // For each column create a line with header: value
                cells.forEach((cell, idx) => {
                    const line = document.createElement('div');
                    line.className = 'mobile-row';
                    const label = document.createElement('div');
                    label.className = 'mobile-label';
                    label.textContent = thead[idx] || '';
                    const value = document.createElement('div');
                    value.className = 'mobile-value';
                    // preserve innerHTML for small markup like badges/buttons
                    value.innerHTML = cell.innerHTML;
                    line.appendChild(label);
                    line.appendChild(value);
                    card.appendChild(line);
                });

                listContainer.appendChild(card);
            });

            createPaginator(pages.length, pageIndex + 1, pagerContainer, (p) => renderPage(p - 1));
            // After rendering page, ensure action buttons wrap nicely
            requestAnimationFrame(() => {
                listContainer.querySelectorAll('.mobile-card').forEach(card => {
                    const actionButtons = card.querySelectorAll('button, a.btn, .btn');
                    if (actionButtons.length > 0) {
                        // allow wrapping and consistent sizing via CSS classes
                        actionButtons.forEach(b => b.classList.add('mobile-action-btn'));
                    }
                });
            });
        }

        renderPage(0);

        // Insert after the table
        table.parentNode.insertBefore(wrapper, table.nextSibling);

        // If table has tfoot totals, render a persistent totals card below pager
        const tfoot = table.querySelector('tfoot');
        if (tfoot) {
            const totalsWrapper = document.createElement('div');
            totalsWrapper.className = 'mobile-totals card';
            totalsWrapper.style.marginTop = '0.6rem';
            // iterate over tfoot rows and cells
            Array.from(tfoot.querySelectorAll('tr')).forEach(tr => {
                const cells = Array.from(tr.children);
                const row = document.createElement('div');
                row.className = 'mobile-row mobile-total-row';
                cells.forEach((cell, idx) => {
                    const label = document.createElement('div');
                    label.className = 'mobile-label';
                    label.textContent = cell.tagName.toLowerCase() === 'th' ? (cell.textContent || '').trim() : '';
                    const value = document.createElement('div');
                    value.className = 'mobile-value';
                    value.innerHTML = cell.innerHTML;
                    row.appendChild(label);
                    row.appendChild(value);
                });
                totalsWrapper.appendChild(row);
            });
            wrapper.parentNode.insertBefore(totalsWrapper, wrapper.nextSibling);
        }

        return wrapper;
    }

    function init() {
        const tables = Array.from(document.querySelectorAll('table'));
        tables.forEach(table => {
            // Skip tables that are in PDF templates or explicitly excluded
            if (table.closest('.pdf') || table.closest('form') || table.dataset.skipMobile === 'true') return;
            buildMobileListFromTable(table);
        });

        // Toggle visibility based on resize
        function updateVisibility() {
            const mobile = window.innerWidth <= MOBILE_BREAKPOINT;
            document.querySelectorAll('.mobile-table-list').forEach(el => {
                el.style.display = mobile ? 'block' : 'none';
            });
            document.querySelectorAll('table').forEach(t => {
                if (t.closest('.pdf') || t.dataset.skipMobile === 'true') return;
                t.style.display = mobile ? 'none' : '';
            });
        }

        updateVisibility();
        window.addEventListener('resize', updateVisibility);
    }

    init();
});
