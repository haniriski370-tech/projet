document.addEventListener('DOMContentLoaded', () => {

  // toast message helper
  function showToast(message, duration = 1500) {
    let t = document.querySelector('.toast');
    if (!t) {
      t = document.createElement('div');
      t.className = 'toast';
      document.body.appendChild(t);
    }
    t.textContent = message;
    t.classList.add('show');
    clearTimeout(t._hideTimer);
    t._hideTimer = setTimeout(() => t.classList.remove('show'), duration);
  }

  // confirm delete links
  const deleteLinks = document.querySelectorAll('a[href*="delete"]');
  deleteLinks.forEach(link => {
    link.addEventListener('click', e => {
      if (!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
        showToast('Deletion canceled', 1000);
      }
    });
  });

  // optional: auto-toast for actions like add/edit
  const actionForms = document.querySelectorAll('form');
  actionForms.forEach(form => {
    form.addEventListener('submit', () => {
      const action = form.querySelector('button[type="submit"]').name;
      if (action === 'add') showToast('Item added successfully!');
      if (action === 'edit') showToast('Item updated successfully!');
    });
  });

});
