class Logger {
    constructor() {
        //
    }

    logTrace(groupTitle, groupLines = []) {
        if (debug && groupLines.length > 0) {
            console.groupCollapsed(groupTitle);

            for (var i = 0; i < groupLines.length; i++) {
                console.log(groupLines[i]);
            }

            console.groupEnd();
        }
    }
}