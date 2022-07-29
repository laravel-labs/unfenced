function setTab(tab, group, save) {
    save = typeof save === 'undefined' ? true : save

    document
        .querySelectorAll(`.tabbed-code[data-group="${group}"] .tabbed-code-nav-button`)
        .forEach(el => el.classList.remove('active'))
    document
        .querySelectorAll(`.tabbed-code[data-group="${group}"] .code-container`)
        .forEach(el => el.classList.remove('active'))
    document
        .querySelectorAll(`.tabbed-code[data-group="${group}"] [data-tab="${tab}"]`)
        .forEach(el => el.classList.add('active'))

    if (save) {
        saveTab(tab, group)
    }
}

function getTabs() {
    try {
        return JSON.parse(localStorage.tabs)
    } catch {
        return {}
    }
}

function saveTab(tab, group) {
    localStorage.tabs = JSON.stringify({
        ...getTabs(),
        [group]: tab,
    })
}

function restoreTabs() {
    Object.entries(getTabs()).forEach(([group, tab]) => setTab(tab, group, false))
}

restoreTabs()
