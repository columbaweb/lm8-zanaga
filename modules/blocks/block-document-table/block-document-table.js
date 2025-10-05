document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.file-table.month .file .date').forEach(el => {
    const parts = el.textContent.trim().split(' ');
    if (parts.length === 3) {
      el.textContent = `${parts[1]} ${parts[2]}`;
    }
  });
});
