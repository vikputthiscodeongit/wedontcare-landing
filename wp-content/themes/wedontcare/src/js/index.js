import debounce from "lodash/debounce";

import stylesheet from "../scss/style.scss";

(function() {
    // Helpers
    // Check if stylesheet has been loaded
    function cssLoaded() {
        return cssValue(body, "display") === "flex";
    }

    // Get a CSS property value
    function cssValue(el, prop) {
        const styles = window.getComputedStyle(el),
              value  = styles.getPropertyValue(prop);

        return value;
    }

    // Convert CSS unit to a number
    function cssUnitToNo(unit) {
        let sliceEnd = -2;

        if (unit.indexOf("rem") > -1) {
            sliceEnd = -3;
        }

        return Number(unit.slice(0, sliceEnd));
    }

    // Check if viewport is above given breakpoint
    function aboveBreakpoint(bpName) {
        if (!bpName)
            return true;

        const bp = stylesheet[`${bpName}Breakpoint`];

        if (typeof bp === "undefined") {
            console.error("The given breakpoint either doesn't exist or hasn't been exported to JavaScript.");
        }

        return window.matchMedia(`(min-width: ${bp})`).matches;
    }

    // Valide an email address against the RFC 5322 specification. See also https://stackoverflow.com/a/201378/6396604 & https://emailregex.com/.
    function isValidEmail(address) {
        const regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i;

        return regEx.test(address);
    }

    const html = document.documentElement,
          body = document.body;

    // Event handlers
    document.addEventListener("DOMContentLoaded", function() {
        html.classList.replace("no-js", "js");

        inputDeviceDetector();

        main.init();

        video.init();

        wpcf7.init();

        reversedRow.init();

        xScroller.init();

        fpContent.init();
    });


    // Input devices
    function inputDeviceDetector() {
        console.log("In inputDeviceDetector().");

        body.addEventListener("mousedown", function() {
            body.classList.add("using-mouse");
        });

        body.addEventListener("keydown", function() {
            body.classList.remove("using-mouse");
        });
    }


    // Main
    let main = {};

    main.init = function() {
        console.log("In main.init().");

        if (!body.classList.contains("cover-fullvh")) {
            console.log("<body> should NOT cover the entire viewport. Exiting function!");

            return;
        }

        if (!cssLoaded()) {
            const timeout = 1000;

            console.log(`CSS hasn't been loaded yet. Running function in ${timeout} ms!`);

            setTimeout(main.init, timeout);

            return;
        }

        main.heightFixer();

        window.addEventListener("resize", debounce(function() {
            main.heightFixer();
        }, 25));
    };

    main.el = document.querySelector("main");

    main.heightFixer = function() {
        console.log("In main.heightFixer().");

        let vh = html.clientHeight;

        const vhFixedMinTreshold = 640;

        if (body.classList.contains("cover-fullvh--fixed-min") && vh < vhFixedMinTreshold) {
            if (!aboveBreakpoint("md") && vh < 568) {
                vh = 568;
            } else if (aboveBreakpoint("md") && vh < vhFixedMinTreshold) {
                vh = vhFixedMinTreshold;
            }
        } else if (body.classList.contains("cover-fullvh--dynamic")) {
            body.classList.remove("covers-fullvh");

            const bh = body.clientHeight;

            if (vh > bh) {
                body.classList.add("covers-fullvh");
            }
        }

        if (vh > 1080) {
            vh = 1080;
        }

        main.el.style.setProperty("--vh", `${vh}px`);
    };


    // Video
    let video = {};

    video.init = function() {
        console.log("In video.init().");

        if (video.els.length === 0) {
            console.log("No <video>s found on this page. Exiting function!");

            return;
        }

        video.els.forEach((videoEl) => {
            if (!videoEl.hasAttribute("autoplay"))
                return;

            video.removeControls(videoEl);
        });
    };

    video.els = document.querySelectorAll("video");

    video.removeControls = function(targetVideo) {
        console.log("In video.removeControls().");

        targetVideo.removeAttribute("controls");
    };


    // Contact Form 7
    let wpcf7 = {};

    wpcf7.init = function() {
        console.log("In wpcf7.init().");

        if (wpcf7.els.length === 0) {
            console.log("No WPCF7 elements found on this page. Exiting function!");

            return;
        }

        wpcf7.els.forEach((wpcf7El) => {
            wpcf7.changeAttributes(wpcf7El);

            wpcf7.createSubmitStatusEl(wpcf7El);

            const inputs = wpcf7El.querySelectorAll(".wpcf7-form .form__input");

            inputs.forEach((input) => {
                if (
                    input.classList.contains("wpcf7-validates-as-required") &&
                    // input.value isn't necessarily always empty on form initialization,
                    // Firefox for example retains <input> values when a page is refreshed.
                    input.value === ""
                ) {
                    wpcf7.setStateInvalid(input);
                }

                input.addEventListener("input", function() {
                    wpcf7.inputValidator(input);
                });
            });
        });
    };

    wpcf7.els = document.querySelectorAll(".wpcf7");

    wpcf7.changeAttributes = function(wpcf7El) {
        console.log("In wpcf7.changeAttributes().");

        wpcf7El.classList.add("form");

        if (body.classList.contains("page-template-front-page")) {
            if (wpcf7El.closest(".fp-content")) {
                wpcf7El.classList.add("form--width-small");
                wpcf7El.setAttribute("id", "form-mailing");
            }
        }
    };

    wpcf7.createSubmitStatusEl = function(wpcf7El) {
        console.log("In wpcf7.createSubmitStatusEl().");

        const wpcf7Form           = wpcf7El.querySelector(".wpcf7-form"),
              inlineSubmitWrapper = wpcf7Form.querySelector(".form__field--inline-send");

        if (!inlineSubmitWrapper)
            return;

        const inputWrapper = inlineSubmitWrapper.querySelector(".wpcf7-form-control-wrap"),
              input        = inputWrapper.querySelector("input");

        const submitStatusEl = document.createElement("span");
        submitStatusEl.className = "wpcf7-submit-status";

        input.parentNode.insertBefore(submitStatusEl, input.nextElementSibling);

        wpcf7.setSubmitStatusEl(wpcf7El);
    };

    wpcf7.setSubmitStatusEl = function(wpcf7El) {
        console.log("In wpcf7.setSubmitStatusEl().");

        const wpcf7Form      = wpcf7El.querySelector(".wpcf7-form"),
              submitButton   = wpcf7Form.querySelector("[type='submit']"),
              submitStatusEl = wpcf7Form.querySelector(".wpcf7-submit-status");

        wpcf7El.addEventListener("wpcf7beforesubmit", function() {
            submitStatusEl.textContent = "Submitting...";
        });

        wpcf7El.addEventListener("wpcf7submit", function(e) {
            let timeout = 0;

            if (e.detail.apiResponse.status === "mail_sent") {
                timeout = 2000;

                submitStatusEl.textContent = "Success!";
            }

            setTimeout(function() {
                submitStatusEl.textContent = "";
            }, timeout);
        });

        wpcf7El.addEventListener("wpcf7beforesubmit", function() {
            submitButton.setAttribute("disabled", true);
        });

        wpcf7El.addEventListener("wpcf7submit", function() {
            setTimeout(function() {
                submitButton.removeAttribute("disabled");
            }, 2000);
        });
    };

    wpcf7.inputValidator = function(input) {
        console.log("In wpcf7.inputValidator().");

        const type = input.getAttribute("type");

        if (
            (type === "email" && isValidEmail(input.value)) ||
            (type !== "email" && input.value !== "")
        ) {
            wpcf7.setStateValid(input);
        } else {
            wpcf7.setStateInvalid(input);
        }
    };

    wpcf7.setStateValid = function(input) {
        console.log("In wpcf7.setStateValid().");

        input.setAttribute("aria-invalid", false);
        input.parentElement.classList.remove("is-invalid");

        input.parentElement.classList.add("is-valid");
    };

    wpcf7.setStateInvalid = function(input) {
        console.log("In wpcf7.setStateInvalid().");

        input.parentElement.classList.remove("is-valid");

        input.setAttribute("aria-invalid", true);
        input.parentElement.classList.add("is-invalid");
    };


    // .row--lg-direction-reverse
    let reversedRow = {};

    reversedRow.init = function() {
        console.log("In reversedRow.init().");

        if (reversedRow.els.length === 0) {
            console.log("No .row--lg-direction-reverse found on this page. Exiting function!");

            return;
        }

        reversedRow.els.forEach((rowEl) => {
            const boxElsWithLinks = rowEl.querySelectorAll(".box a");

            if (boxElsWithLinks.length === 0)
                return;

            const rowChildElsArr = [...rowEl.children];

            let fromBp = false;

            const rowClasses = rowEl.className.split(" ");

            const bpRegEx = /row--([a-z]{2})-direction-reverse/;

            rowClasses.forEach((cls) => {
                const match = cls.match(bpRegEx);

                if (match !== null) {
                    fromBp = match[1];
                }
            });

            boxElsWithLinks.forEach((link) => {
                reversedRow.fixBoxOrder(link, rowChildElsArr, fromBp);

                window.addEventListener("resize", debounce(function() {
                    reversedRow.fixBoxOrder(link, rowChildElsArr, fromBp);
                }, 25));
            });
        });
    };

    reversedRow.els = document.querySelectorAll(".row[class*=direction-reverse]");

    reversedRow.fixBoxOrder = function(link, rowChildElsArr, fromBp) {
        console.log("In reversedRow.fixBoxOrder().");

        if (aboveBreakpoint(fromBp)) {
            if (!link.hasAttribute("tabindex")) {
                const box = link.closest(".box");

                const targetTabindex = rowChildElsArr.length - rowChildElsArr.indexOf(box);

                link.setAttribute("tabindex", targetTabindex);
            }
        } else {
            if (link.hasAttribute("tabindex")) {
                link.removeAttribute("tabindex");
            }
        }
    };


    // Horizontal scroll container
    // Based on https://stackoverflow.com/a/15343916/6396604.
    let xScroller = {};

    xScroller.init = function() {
        console.log("In xScroller.init().");

        if (xScroller.els.length === 0) {
            console.log("Exiting function - no horizontal scrollers found on this page!");

            return;
        }

        xScroller.els.forEach((scroller) => {
            let fromBp = false;

            const scrollerClasses = scroller.className.split(" ");

            const bpRegEx = /x-scroller--([a-z]{2})/;

            scrollerClasses.forEach((cls) => {
                const match = cls.match(bpRegEx);

                if (match !== null) {
                    fromBp = match[1];
                }
            });

            // All browsers but Gecko-based ones.
            scroller.addEventListener("mousewheel", function(e) {
                xScroller.scroll(e, scroller, fromBp);
            });
            // Gecko-based browsers.
            scroller.addEventListener("DOMMouseScroll", function(e) {
                xScroller.scroll(e, scroller, fromBp);
            });
        });
    };

    xScroller.els = document.querySelectorAll(".x-scroller");

    xScroller.scroll = function(e, scroller, fromBp) {
        console.log("In xScroller.scroll().");

        if (aboveBreakpoint(fromBp)) {
            e.preventDefault();

            const delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));

            scroller.scrollLeft -= (delta * 100);
        }
    };


    // Front page - <video>
    let fpContent = {};

    fpContent.init = function() {
        console.log("In fpContent.init().");

        if (!fpContent.mediaEl) {
            console.log("This is either not the front page, or it is but no <video> is present. Exiting function!");

            return;
        }

        if (!cssLoaded()) {
            const timeout = 1000;

            console.log(`CSS hasn't been loaded yet. Running function in ${timeout} ms!`);

            setTimeout(fpContent.init, timeout);

            return;
        }

        fpContent.sizeFixer();

        window.addEventListener("resize", debounce(function() {
            fpContent.sizeFixer();
        }, 25));
    };

    fpContent.el = document.querySelector(".fp-content");

    fpContent.mediaEl = document.querySelector(".fp-content > .media");

    fpContent.sizeFixer = function() {
        console.log("In fpContent.sizeFixer().");

        const bodyFontSizeUl = cssUnitToNo(cssValue(body, "font-size"));
        const fpCRowOuterHeightMp = cssUnitToNo(stylesheet.fpCRowOuterHeight);

        const fpCRowOuterHeight = bodyFontSizeUl * fpCRowOuterHeightMp;
        const fpContentElHeight = fpContent.el.getBoundingClientRect().height;

        const mediaWidth = fpContent.mediaEl.getBoundingClientRect().width;
        const mediaTargetHeight = fpContentElHeight - (fpCRowOuterHeight * 2);

        if (mediaWidth > mediaTargetHeight) {
            const rowHeights = `${fpCRowOuterHeight}px ${mediaTargetHeight}px ${fpCRowOuterHeight}px`;

            fpContent.el.style.gridTemplateRows = rowHeights;
        } else if (fpContent.el.style.gridTemplateRows !== "") {
            fpContent.el.style.gridTemplateRows = "";
        }
    };
})();
