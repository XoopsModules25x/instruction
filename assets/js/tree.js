function instr_tree_toggle(event) {
    event = event || window.event;
    var clickedElem = event.target || event.srcElement;

    // Если кликнули по +/-
    if (instr_hasClass(clickedElem, 'InstrTreeExpand')) {
        // Node, на который кликнули
        var node = clickedElem.parentNode;
        // Если кликнули по "Пустой странице"
    } else if (instr_hasClass(clickedElem, 'InstrTreeEmptyPage')) {
        // InstrTreeContent
        var node = clickedElem.parentNode;
        // Node
        node = node.parentNode;
        // Если кликнули не в том месте
    } else {
        // Прерываем
        return
    }

    // Если у Node есть потомки
    if (instr_hasClass(node, 'InstrTreeExpandLeaf')) {
        return // клик на листе
    }

    // определить новый класс для узла
    var newClass = instr_hasClass(node, 'InstrTreeExpandOpen') ? 'InstrTreeExpandClosed' : 'InstrTreeExpandOpen'
    // заменить текущий класс на newClass
    // регексп находит отдельно стоящий open|close и меняет на newClass
    var re = /(^|\s)(InstrTreeExpandOpen|InstrTreeExpandClosed)(\s|$)/
    node.className = node.className.replace(re, '$1' + newClass + '$3')
}

function instr_hasClass(elem, className) {
    return new RegExp("(^|\\s)" + className + "(\\s|$)").test(elem.className)
}
