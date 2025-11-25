// Inicialização do select custom Ano
    (function() {
      const select = document.getElementById('customSelectAno');
      const selected = select.querySelector('.selected');
      const options = select.querySelector('.options');
      const hiddenInput = select.querySelector('input[type="hidden"]');

      if (hiddenInput.value) {
        if (hiddenInput.value === "all") {
          selected.textContent = "Todos";
        } else {
          const initialOption = Array.from(options.children).find(opt => opt.dataset.value === hiddenInput.value);
          if (initialOption) selected.textContent = initialOption.textContent;
        }
      }

      selected.addEventListener('click', () => {
        const isOpen = select.classList.toggle('open');
        select.setAttribute('aria-expanded', isOpen);
      });

      options.querySelectorAll('li').forEach(option => {
        option.addEventListener('click', () => {
          selected.textContent = option.textContent;
          // normaliza 'all' para string vazia
          hiddenInput.value = option.dataset.value === 'all' ? '' : option.dataset.value;
          select.classList.remove('open');
          select.setAttribute('aria-expanded', false);
          hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
          // atualizar chips visuais e layout do form
          if (typeof atualizarFiltros === 'function') atualizarFiltros();
        });
      });

      document.addEventListener('click', e => {
        if (!select.contains(e.target)) {
          select.classList.remove('open');
          select.setAttribute('aria-expanded', false);
        }
      });

      select.addEventListener('keydown', e => {
        if (!select.classList.contains('open') && (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ')) {
          e.preventDefault();
          select.classList.add('open');
          select.setAttribute('aria-expanded', true);
          options.querySelector('li').focus();
          return;
        }
        if (select.classList.contains('open')) {
          const focusable = Array.from(options.querySelectorAll('li'));
          let index = focusable.indexOf(document.activeElement);
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            index = (index + 1) % focusable.length;
            focusable[index].focus();
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            index = (index - 1 + focusable.length) % focusable.length;
            focusable[index].focus();
          } else if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            if (focusable.includes(document.activeElement)) {
              document.activeElement.click();
            }
          } else if (e.key === 'Escape') {
            e.preventDefault();
            select.classList.remove('open');
            select.setAttribute('aria-expanded', false);
            selected.focus();
          }
        }
      });
    })();