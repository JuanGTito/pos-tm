/**
 * Sistema de Atajos de Teclado Configurables
 * Soporta combinaciones: Ctrl, Shift, Alt
 */

export class KeyboardShortcuts {
    constructor() {
        this.shortcuts = {};
        this.isInputFocused = false;
        this.init();
    }

    /**
     * Inicializar el sistema desde la configuración de Laravel
     */
    init() {
        // Cargar configuración desde meta tag
        const configScript = document.querySelector('script[data-shortcuts]');
        if (configScript) {
            const config = JSON.parse(configScript.textContent);
            this.registerShortcuts(config.shortcuts);
        }

        this.attachEventListeners();
    }

    /**
     * Registrar atajos desde un objeto de configuración
     * @param {Object} shortcuts
     */
    registerShortcuts(shortcuts) {
        Object.entries(shortcuts).forEach(([key, config]) => {
            this.register(key, config);
        });
    }

    /**
     * Registrar un atajo individual
     * @param {String} keyCombo - Ej: "ctrl+s", "f1", "shift+alt+n"
     * @param {Object} config - {action, route, description}
     */
    register(keyCombo, config) {
        this.shortcuts[keyCombo.toLowerCase()] = config;
    }

    /**
     * Adjuntar listeners de eventos
     */
    attachEventListeners() {
        document.addEventListener('keydown', (e) => this.handleKeydown(e));
        document.addEventListener('focus', () => { this.isInputFocused = true; }, true);
        document.addEventListener('blur', () => { this.isInputFocused = false; }, true);
    }

    /**
     * Manejar evento keydown
     */
    handleKeydown(e) {
        const keyCombo = this.getKeyCombo(e);
        const shortcut = this.shortcuts[keyCombo];

        if (!shortcut) return;

        // No interceptar si estamos en un input y es una tecla reservada
        if (this.isInputFocused && this.isReservedInInputs(keyCombo)) {
            return;
        }

        e.preventDefault();
        this.executeShortcut(shortcut);
    }

    /**
     * Obtener combinación de teclas normalizada
     */
    getKeyCombo(e) {
        let combo = [];

        if (e.ctrlKey || e.metaKey) combo.push('ctrl');
        if (e.shiftKey) combo.push('shift');
        if (e.altKey) combo.push('alt');

        const key = e.key.toLowerCase();
        if (!['control', 'shift', 'alt', 'meta'].includes(key)) {
            combo.push(key);
        }

        return combo.join('+');
    }

    /**
     * Verificar si es una tecla reservada en inputs
     */
    isReservedInInputs(keyCombo) {
        const reserved = ['ctrl+c', 'ctrl+x', 'ctrl+v', 'ctrl+z', 'ctrl+a'];
        return reserved.includes(keyCombo);
    }

    /**
     * Ejecutar el atajo
     */
    executeShortcut(shortcut) {
        if (shortcut.route) {
            window.location.href = this.getRouteUrl(shortcut.route);
        }

        if (shortcut.action) {
            this.executeAction(shortcut.action);
        }
    }

    /**
     * Obtener URL de ruta (compatible con Laravel)
     */
    getRouteUrl(routeName) {
        // Usar la función route() de Laravel si está disponible
        if (typeof route !== 'undefined') {
            return route(routeName);
        }
        return `/${routeName}`;
    }

    /**
     * Ejecutar acciones personalizadas
     */
    executeAction(action) {
        switch (action) {
            case 'toggleHelp':
                this.toggleHelp();
                break;
            case 'focusNextInput':
                this.focusNextInput();
                break;
            case 'focusPrevInput':
                this.focusPrevInput();
                break;
            case 'closeModals':
                this.closeModals();
                break;
            case 'submitForm':
                this.submitForm();
                break;
            case 'focusSearch':
                this.focusSearch();
                break;
            case 'clearSearch':
                this.clearSearch();
                break;
            default:
                console.warn(`Acción no reconocida: ${action}`);
        }
    }

    /**
     * Alternar ayuda de atajos
     */
    toggleHelp() {
        const help = document.querySelector('[data-shortcuts-help]');
        if (help) {
            help.classList.toggle('hidden');
        } else {
            this.showShortcutsHelp();
        }
    }

    /**
     * Mostrar tabla de atajos disponibles
     */
    showShortcutsHelp() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-lg max-w-2xl max-h-96 overflow-y-auto">
                <h2 class="text-xl font-bold mb-lg">Atajos de Teclado Disponibles</h2>
                <table class="w-full text-sm">
                    <tr class="border-b">
                        <th class="text-left px-md py-sm font-semibold">Atajo</th>
                        <th class="text-left px-md py-sm font-semibold">Descripción</th>
                    </tr>
                    ${Object.entries(this.shortcuts)
                        .map(([key, config]) => `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-md py-sm font-mono text-gray-700">${key.toUpperCase()}</td>
                            <td class="px-md py-sm">${config.description || ''}</td>
                        </tr>
                    `).join('')}
                </table>
                <button onclick="this.closest('.fixed').remove()" class="mt-lg bg-gray-700 text-white px-lg py-sm rounded-base">
                    Cerrar
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    }

    /**
     * Enfocar siguiente input
     */
    focusNextInput() {
        const inputs = Array.from(document.querySelectorAll('input, textarea, select, button, a[tabindex]'));
        const current = document.activeElement;
        const index = inputs.indexOf(current);
        if (index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    }

    /**
     * Enfocar input anterior
     */
    focusPrevInput() {
        const inputs = Array.from(document.querySelectorAll('input, textarea, select, button, a[tabindex]'));
        const current = document.activeElement;
        const index = inputs.indexOf(current);
        if (index > 0) {
            inputs[index - 1].focus();
        }
    }

    /**
     * Cerrar todos los modales
     */
    closeModals() {
        document.querySelectorAll('[data-modal]').forEach(modal => {
            modal.style.display = 'none';
        });
    }

    /**
     * Enviar el primer formulario disponible
     */
    submitForm() {
        const form = document.querySelector('form');
        if (form) {
            form.submit();
        }
    }

    /**
     * Enfocar barra de búsqueda
     */
    focusSearch() {
        const search = document.querySelector('input[type="search"]') || 
                       document.querySelector('input[placeholder*="search" i]') ||
                       document.querySelector('input[placeholder*="búsqueda" i]');
        if (search) {
            search.focus();
            search.select();
        }
    }

    /**
     * Limpiar búsqueda
     */
    clearSearch() {
        const search = document.querySelector('input[type="search"]') || 
                       document.querySelector('input[placeholder*="search" i]') ||
                       document.querySelector('input[placeholder*="búsqueda" i]');
        if (search) {
            search.value = '';
            search.focus();
        }
    }
}

// Inicializar automáticamente
document.addEventListener('DOMContentLoaded', () => {
    window.keyboardShortcuts = new KeyboardShortcuts();
});
