import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function calculerStock(index) {
  const stockDebutInput = document.getElementById(`stock_debut_${index}`);
  const qteRecuInput = document.getElementById(`qte_recu_${index}`);
  const qteEnStockInput = document.getElementById(`qte_en_stock_${index}`);

  if (!stockDebutInput || !qteRecuInput || !qteEnStockInput) {
    return;
  }

  const stockDebut = parseFloat(stockDebutInput.value) || 0;
  const qteRecu = parseFloat(qteRecuInput.value) || 0;

  if (stockDebutInput.value && !qteRecuInput.value) {
    qteEnStockInput.value = stockDebut;
  } else if (!stockDebutInput.value && qteRecuInput.value) {
    qteEnStockInput.value = '';
  } else if (stockDebutInput.value && qteRecuInput.value) {
    qteEnStockInput.value = stockDebut + qteRecu;
  } else {
    qteEnStockInput.value = '';
  }
  
}

function calculerStockSecurite(index) {

  const stockDebutInput = document.getElementById(`stock_debut_${index}`);
  const qteUsedInput = document.getElementById(`qte_used_${index}`);
  const qteEnStockInput = document.getElementById(`qte_en_stock_${index}`);
  const nbJourRuptureInput = document.getElementById(`nb_jour_rupture_${index}`);
  const stkSecuriteInput = document.getElementById(`stk_de_securite_${index}`);
  const ccmaInput = document.getElementById(`ccma_${index}`);
  const cmdTrimSvtInput = document.getElementById(`cmd_trim_svt_${index}`);
  const qteStockFinTrimInput = document.getElementById(`qte_stock_fin_trim_${index}`);

  if (
    !stockDebutInput || !qteUsedInput || !nbJourRuptureInput ||
    !stkSecuriteInput || !ccmaInput || !cmdTrimSvtInput || !qteStockFinTrimInput || !qteEnStockInput
  ) {
    return;
  }
  const stockDebut = parseFloat(stockDebutInput.value) || 0;
  const qteUsed = parseFloat(qteUsedInput.value) || 0;
  const nbJourRupture = parseFloat(nbJourRuptureInput.value) || 0;
  const qteStockFinTrim = parseFloat(qteStockFinTrimInput.value) || 0;
  const qteEnStock = parseFloat(qteEnStockInput.value) || 0;

  if (!stockDebutInput.value) {
    stkSecuriteInput.value = 0;
    ccmaInput.value = 0;
    cmdTrimSvtInput.value = 0;
    return;
  }

  //Verifier le nombre de jours de rupture


  if (nbJourRupture === 0) {
    stkSecuriteInput.value = Math.ceil(qteUsed); // sécurité = conso brute
    const cmma = (qteUsed / 90) * 30;
    ccmaInput.value = Math.ceil(cmma);
    cmdTrimSvtInput.value = Math.ceil((qteUsed + Math.ceil(qteUsed)) - qteStockFinTrim);
  } else if (nbJourRupture > 0 && nbJourRupture < 90) {
    const denom = 90 - nbJourRupture;
    const stkSecurite = (qteUsed * 90) / denom;
    const cmma = (qteUsed / denom) * 30;

    stkSecuriteInput.value = Math.ceil(stkSecurite);
    ccmaInput.value = Math.ceil(cmma);
    cmdTrimSvtInput.value = Math.ceil((Math.ceil(cmma) * 6) - qteStockFinTrim);
  } else {
    stkSecuriteInput.value = 0;
    ccmaInput.value = 0;
    cmdTrimSvtInput.value = 0;
  }

}

function checkInput(index) {
  const inputs = {
    stockDebut: document.getElementById(`stock_debut_${index}`),
    qteRecu: document.getElementById(`qte_recu_${index}`),
    qteUsed: document.getElementById(`qte_used_${index}`),
    nbBeneficaire: document.getElementById(`nb_beneficiaire_${index}`),
    perimee: document.getElementById(`perimee_${index}`),
    perteAvarie: document.getElementById(`perte_avarie_${index}`),
    qteRetCameg: document.getElementById(`qte_ret_cameg_${index}`),
    qteEnStock: document.getElementById(`qte_en_stock_${index}`),
    nbJourRupture: document.getElementById(`nb_jour_rupture_${index}`),
    stkSecurite: document.getElementById(`stk_de_securite_${index}`),
    ccma: document.getElementById(`ccma_${index}`),
    cmdTrimSvt: document.getElementById(`cmd_trim_svt_${index}`),
    qteStockFinTrim: document.getElementById(`qte_stock_fin_trim_${index}`)
  };

  const isValidDigit = (value) => {
    if (value === '') return true;
    // Vérifier que chaque caractère est un chiffre entre 0 et 9
    for (let i = 0; i < value.length; i++) {
      if (value[i] < '0' || value[i] > '9') {
        return false;
      }
    }
    return true;
  };

  // Validation des champs numériques
  for (const [key, input] of Object.entries(inputs)) {
    if (!input) continue;

    // Nettoyer la valeur en supprimant les caractères non numériques
    input.value = input.value.replace(/[^0-9]/g, '');

    if (!isValidDigit(input.value)) {
      alert(`Le champ "${key}" doit contenir uniquement des chiffres ou être vide.`);
      input.value = '';
      input.focus();
      return false;
    }
  }

  // Vérification : qte_used ne dépasse pas qte_en_stock
  const qteUsed = parseInt(inputs.qteUsed.value, 10) || 0;
  const qteEnStock = parseInt(inputs.qteEnStock.value, 10) || 0;

  if (qteUsed > qteEnStock) {
    alert("La quantité utilisée ne peut pas être supérieure à la quantité en stock.");
    inputs.qteUsed.value = '';
    inputs.qteUsed.focus();
    return false;
  }

  // Vérification : nombre de jours de rupture
  const nbJourRupture = parseInt(inputs.nbJourRupture.value, 10) || 0;

  if (nbJourRupture < 0 || nbJourRupture > 89) {
    alert("Le nombre de jours de rupture doit être compris entre 0 et 89.");
    inputs.nbJourRupture.value = '';
    inputs.nbJourRupture.focus();
    return false;
  }

  return true;
}

document.addEventListener('input', function (e) {
  const targetId = e.target.id;
  const index = targetId.split('_').pop();

  // Valider avant de calculer
  if (!checkInput(index)) {
    return;
  }

  if (targetId.startsWith('stock_debut_') || targetId.startsWith('qte_recu_')) {
    calculerStock(index);
    if (targetId.startsWith('stock_debut_')) {
      calculerStockSecurite(index);
    }
  } else if (targetId.startsWith('qte_used_') || targetId.startsWith('nb_jour_rupture_') || targetId.startsWith('qte_stock_fin_trim_')) {
    calculerStockSecurite(index);
  }
});




