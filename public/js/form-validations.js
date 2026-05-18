(function () {
    "use strict";

    var EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var CARD_REGEX = /^[0-9 ]{16,19}$/;
    var EXPIRY_REGEX = /^(0[1-9]|1[0-2])\/[0-9]{2}$/;
    var CVV_REGEX = /^[0-9]{3,4}$/;

    function normalizeValue(value) {
        return String(value || "").trim();
    }

    function ensureFeedback(field) {
        var sibling = field.nextElementSibling;

        if (sibling && sibling.classList.contains("invalid-feedback")) {
            return sibling;
        }

        var feedback = document.createElement("div");
        feedback.className = "invalid-feedback js-validation-feedback";
        field.insertAdjacentElement("afterend", feedback);
        return feedback;
    }

    function setFieldValidity(field, message) {
        var hasError = Boolean(message);
        var feedback = ensureFeedback(field);

        field.classList.toggle("is-invalid", hasError);
        field.classList.toggle("is-valid", !hasError && normalizeValue(field.value) !== "");
        field.setCustomValidity(hasError ? message : "");
        feedback.textContent = hasError ? message : "";
    }

    function fieldLabel(field) {
        var id = field.getAttribute("id");
        if (!id) {
            return "Este campo";
        }

        var label = document.querySelector('label[for="' + id + '"]');
        return label ? normalizeValue(label.textContent) : "Este campo";
    }

    function isRequiredField(field, form) {
        if (field.required) {
            return true;
        }

        var name = field.name || "";
        var action = form.getAttribute("action") || "";

        if (name === "contrasena" && /\/perfil(?:$|[/?#])/.test(action)) {
            return false;
        }

        return [
            "nombre",
            "correo",
            "email",
            "contrasena",
            "titulo",
            "precio",
            "stock",
            "asunto",
            "mensaje",
            "numero_tarjeta",
            "caducidad",
            "cvv",
        ].includes(name);
    }

    function validateField(field, form) {
        var value = normalizeValue(field.value);
        var label = fieldLabel(field);
        var required = isRequiredField(field, form);
        var minAttr = field.getAttribute("min");
        var maxAttr = field.getAttribute("max");
        var minLengthAttr = field.getAttribute("minlength");
        var maxLengthAttr = field.getAttribute("maxlength");
        var type = (field.getAttribute("type") || "").toLowerCase();
        var name = field.name || "";
        var message = "";

        if (required && value === "") {
            return label + " es obligatorio.";
        }

        if (!required && value === "") {
            return "";
        }

        if (type === "email" || name === "correo" || name === "email") {
            if (!EMAIL_REGEX.test(value)) {
                return "Introduce un correo con formato válido.";
            }
        }

        if (type === "number") {
            var numericValue = Number(value);
            if (Number.isNaN(numericValue)) {
                return label + " debe ser un número válido.";
            }

            if (minAttr !== null && numericValue < Number(minAttr)) {
                return label + " debe ser mayor o igual que " + minAttr + ".";
            }

            if (maxAttr !== null && numericValue > Number(maxAttr)) {
                return label + " debe ser menor o igual que " + maxAttr + ".";
            }
        }

        if (minLengthAttr !== null && value.length < Number(minLengthAttr)) {
            return label + " debe tener al menos " + minLengthAttr + " caracteres.";
        }

        if (maxLengthAttr !== null && value.length > Number(maxLengthAttr)) {
            return label + " no puede superar " + maxLengthAttr + " caracteres.";
        }

        if (name === "contrasena" && value !== "" && value.length < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }

        if (name === "contrasena_confirmation") {
            var passwordField = form.querySelector('[name="contrasena"]');
            var passwordValue = passwordField ? normalizeValue(passwordField.value) : "";

            if (passwordValue !== "" && value !== passwordValue) {
                return "La confirmación de la contraseña no coincide.";
            }
        }

        if (name === "numero_tarjeta" && !CARD_REGEX.test(value)) {
            return "El número de tarjeta debe tener 16 dígitos.";
        }

        if (name === "caducidad" && !EXPIRY_REGEX.test(value)) {
            return "La caducidad debe tener el formato MM/AA.";
        }

        if (name === "cvv" && !CVV_REGEX.test(value)) {
            return "El CVV debe tener 3 o 4 dígitos.";
        }

        if (field.pattern && value !== "") {
            var pattern = new RegExp(field.pattern);
            if (!pattern.test(value)) {
                message = field.getAttribute("title") || (label + " no tiene el formato esperado.");
            }
        }

        return message;
    }

    function formFields(form) {
        return Array.from(form.querySelectorAll("input, select, textarea"))
            .filter(function (field) {
                var type = (field.getAttribute("type") || "").toLowerCase();
                return !field.disabled && !["hidden", "submit", "button", "reset"].includes(type);
            });
    }

    function validateForm(form) {
        var fields = formFields(form);
        var firstInvalid = null;

        fields.forEach(function (field) {
            var message = validateField(field, form);
            setFieldValidity(field, message);
            if (!firstInvalid && message) {
                firstInvalid = field;
            }
        });

        if (firstInvalid) {
            firstInvalid.focus();
            return false;
        }

        return true;
    }

    function setupForm(form) {
        form.setAttribute("novalidate", "novalidate");

        formFields(form).forEach(function (field) {
            field.addEventListener("input", function () {
                var message = validateField(field, form);
                setFieldValidity(field, message);
            });

            field.addEventListener("blur", function () {
                var message = validateField(field, form);
                setFieldValidity(field, message);
            });
        });

        form.addEventListener("submit", function (event) {
            if (!validateForm(form)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }

    function init() {
        var forms = document.querySelectorAll("form");
        forms.forEach(function (form) {
            if (!form.hasAttribute("data-no-js-validation")) {
                setupForm(form);
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
