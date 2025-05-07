/**
 * Composable simplifié qui fournit uniquement la fonction de création de nuancier
 * 
 * @returns {Function} La fonction pour créer un nuancier
 */
export function useColorPalette() {
	
/**
 * Génère un nuancier uniformément réparti à partir d'une couleur de référence
 * 
 * @param {string} baseColor - Couleur de référence au format hexadécimal (ex: "#5AB7EE")
 * @param {number} count - Nombre de couleurs à générer
 * @returns {Array} Un tableau contenant toutes les couleurs générées
 */
function createPalette(baseColor, count) {
	// Validation des paramètres d'entrée
	if (!baseColor || typeof baseColor !== 'string' || !baseColor.match(/^#[0-9A-F]{6}$/i)) {
	throw new Error('La couleur de base doit être au format hexadécimal (ex: "#5AB7EE")');
	}
	
	if (!count || typeof count !== 'number' || count < 1) {
	throw new Error('Le nombre de couleurs doit être un nombre positif');
	}
	
	// Convertir la couleur hex en RGB
	const r = parseInt(baseColor.slice(1, 3), 16);
	const g = parseInt(baseColor.slice(3, 5), 16);
	const b = parseInt(baseColor.slice(5, 7), 16);
	
	// Initialiser l'objet résultat
	const palette = [];
	
	// Calculer le nombre de couleurs de chaque côté
	const baseColorIndex = Math.ceil(count / 2);
	
	for (let i = 1; i <= count; i++) {
		let newR, newG, newB;
		
		if (i < baseColorIndex) {
			// Couleurs plus claires
			const factor = i / baseColorIndex;
			newR = Math.round(r * factor + 255 * (1 - factor));
			newG = Math.round(g * factor + 255 * (1 - factor));
			newB = Math.round(b * factor + 255 * (1 - factor));
		} else if (i === baseColorIndex) {
			// Couleur de base
			newR = r;
			newG = g;
			newB = b;
		} else {
			// Couleurs plus foncées
			const factor = (count - i) / (count - baseColorIndex);
			newR = Math.round(r * factor);
			newG = Math.round(g * factor);
			newB = Math.round(b * factor);
		}
		
		// Convertir en hexadécimal
		const hexR = newR.toString(16).padStart(2, '0');
		const hexG = newG.toString(16).padStart(2, '0');
		const hexB = newB.toString(16).padStart(2, '0');
		
		palette.push(`#${hexR}${hexG}${hexB}`);
	}
	
	return palette;
}

/**
 * Permet d'ajouter une opacité à une couleur hexadécimale (format #RRGGBBAA)
 * @param {string} hexColor - Couleur au format hexadécimal (#RRGGBB)
 * @param {number} opacity - Valeur d'opacité entre 0 (transparent) et 1 (opaque)
 * @returns {string} Couleur avec opacité, au format #RRGGBBAA
 */
function addOpacity(hexColor, opacity) {
	// Validation des paramètres
	if (!hexColor || typeof hexColor !== 'string' || !hexColor.match(/^#?[0-9A-F]{6}$/i)) {
	  throw new Error('La couleur doit être au format hexadécimal (ex: "#5AB7EE")');
	}
	
	if (opacity < 0 || opacity > 1 || typeof opacity !== 'number') {
	  throw new Error('L\'opacité doit être un nombre entre 0 et 1');
	}
	
	// Enlever le # si présent
	const hex = hexColor.startsWith('#') ? hexColor.slice(1) : hexColor;
	
	// Convertir l'opacité en valeur hexadécimale
	const alpha = Math.round(opacity * 255).toString(16).padStart(2, '0');
	
	// Retourner la couleur avec la valeur alpha
	return `#${hex}${alpha}`;
}

/**
 * Assombrit une couleur d'un certain niveau
 * @param {string} hexColor - Couleur au format hexadécimal (#RRGGBB)
 * @param {number} level - Niveau d'assombrissement entre 0 (pas de changement) et 1 (noir)
 * @returns {string} Couleur assombrie au format hexadécimal
 */
function darken(hexColor, level) {
	// Validation des paramètres
	if (!hexColor || typeof hexColor !== 'string' || !hexColor.match(/^#?[0-9A-F]{6}$/i)) {
	  throw new Error('La couleur doit être au format hexadécimal (ex: "#5AB7EE")');
	}
	
	if (level < 0 || level > 1 || typeof level !== 'number') {
	  throw new Error('Le niveau d\'assombrissement doit être un nombre entre 0 et 1');
	}
	
	// Enlever le # si présent
	const hex = hexColor.startsWith('#') ? hexColor.slice(1) : hexColor;
	
	// Convertir hex en RGB
	const r = parseInt(hex.slice(0, 2), 16);
	const g = parseInt(hex.slice(2, 4), 16);
	const b = parseInt(hex.slice(4, 6), 16);
	
	// Assombrir en réduisant chaque composante RGB proportionnellement au niveau
	const factor = 1 - level;
	const newR = Math.round(r * factor);
	const newG = Math.round(g * factor);
	const newB = Math.round(b * factor);
	
	// Convertir en hexadécimal
	const hexR = newR.toString(16).padStart(2, '0');
	const hexG = newG.toString(16).padStart(2, '0');
	const hexB = newB.toString(16).padStart(2, '0');
	
	return `#${hexR}${hexG}${hexB}`;
  }

return { createPalette, addOpacity, darken };
}
  
  