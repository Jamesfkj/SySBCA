import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


document.addEventListener('input', function (e) {
  const targetId = e.target.id;
  const index = targetId.split('_').pop();

  if (!checkInput(index)) {
    return;
  }

  if (targetId.startsWith('stock_debut_') || targetId.startsWith('qte_recu_') || targetId.startsWith('qte_used_')) {
    calculerStock(index);
    if (targetId.startsWith('stock_debut_') || targetId.startsWith('qte_used_')) {
      calculerStockSecurite(index);
    }
  } else if (targetId.startsWith('nb_jour_rupture_') || targetId.startsWith('qte_stock_fin_trim_')) {
    calculerStockSecurite(index);
  }
});






