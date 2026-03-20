/* ========================================
   BrewMaster – form.js
   Recipe submission form validation
   COM 2303 | ASB/2023/144
   ======================================== */

function validateField(id, errorId, validationFn, errorMsg) {
  const el  = document.getElementById(id);
  const err = document.getElementById(errorId);
  const val = el.value.trim();
  const isValid = validationFn(val);
  el.classList.toggle('invalid', !isValid);
  el.classList.toggle('valid',   isValid);
  err.textContent = isValid ? '' : errorMsg;
  return isValid;
}

document.getElementById('authorName').addEventListener('blur', () =>
  validateField('authorName', 'err-name', v => v.length >= 2, 'Please enter your name (at least 2 characters).'));

document.getElementById('authorEmail').addEventListener('blur', () =>
  validateField('authorEmail', 'err-email', v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), 'Please enter a valid email address.'));

document.getElementById('recipeName').addEventListener('blur', () =>
  validateField('recipeName', 'err-recipe', v => v.length >= 3, 'Recipe name must be at least 3 characters.'));

document.getElementById('recipeCategory').addEventListener('change', () =>
  validateField('recipeCategory', 'err-category', v => v !== '', 'Please select a category.'));

document.getElementById('ingredients').addEventListener('blur', () =>
  validateField('ingredients', 'err-ingredients', v => v.length >= 10, 'Please list your ingredients (at least 10 characters).'));

document.getElementById('steps').addEventListener('blur', () =>
  validateField('steps', 'err-steps', v => v.length >= 20, 'Please describe the preparation steps (at least 20 characters).'));

function submitRecipe() {
  const checks = [
    validateField('authorName',     'err-name',        v => v.length >= 2,                        'Please enter your name.'),
    validateField('authorEmail',    'err-email',        v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), 'Please enter a valid email address.'),
    validateField('recipeName',     'err-recipe',       v => v.length >= 3,                        'Recipe name must be at least 3 characters.'),
    validateField('recipeCategory', 'err-category',     v => v !== '',                             'Please select a category.'),
    validateField('ingredients',    'err-ingredients',  v => v.length >= 10,                       'Please list your ingredients.'),
    validateField('steps',          'err-steps',        v => v.length >= 20,                       'Please describe the preparation steps.'),
  ];

  if (checks.every(Boolean)) {
    const banner = document.getElementById('formSuccess');
    banner.style.display = 'block';
    banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
    document.getElementById('recipeForm').reset();
    document.querySelectorAll('.brew-input').forEach(el => el.classList.remove('valid', 'invalid'));
    setTimeout(() => { banner.style.display = 'none'; }, 5000);
  } else {
    const firstError = document.querySelector('.brew-input.invalid');
    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}