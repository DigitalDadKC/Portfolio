import { ref } from 'vue';
import { useDeepCopy } from '@/Composables/useDeepCopy';
import { useFormatCurrency } from './useFormatCurrency';

let { deepCopy } = useDeepCopy()
let { formatCurrency } = useFormatCurrency()

export function useProjectMath(job) {
    let ref_line = { 'id': null, 'description': '', 'unit_of_measurement': { 'id': null, 'UOM': '' }, 'price': null, 'quantity': null, 'total': null, 'days': null };
    let ref_scope = { 'id': null, 'name': '', 'area': null, 'days': null, 'total': null, 'lines': [] }

    const addLine = (index) => {
        job.scopes[index].lines.push(deepCopy({...ref_line}))
    }

    const removeLine = (scopeIndex, lineIndex) => {
        job.scopes[scopeIndex].lines.splice(lineIndex, 1);
        scopeTotal(scopeIndex)
        updateScopeDays()
    }

    const addScope = () => {
        job.scopes.push(deepCopy({ ...ref_scope }))
        job.scopes[job.scopes.length-1].lines = []
        job.scopes[job.scopes.length-1].lines.push(deepCopy({...ref_line}))
    }

    const removeScope = (scopeIndex) => {
        job.scopes.splice(scopeIndex, 1)
        updateScopeDays()
        jobTotal()
    }

    const lineTotal = (scopeIndex, lineIndex) => {
        scopeTotal(scopeIndex)
        let line = job.scopes[scopeIndex].lines[lineIndex]
        line.total = Math.round(line.price * line.quantity*100)/100
        line.formatted_total = formatCurrency(line.total)
    }

    const scopeTotal = (scopeIndex) => {
        jobTotal()
        let scope = job.scopes[scopeIndex]
        scope.total = Math.round(job.scopes[scopeIndex].lines.map(line => line.price * line.quantity).reduce((a, b) => a + b, 0)*100)/100
        scope.formatted_total = formatCurrency(scope.total)
    }

    const jobTotal = () => {
        job.total = job.scopes.map(scope => scope.lines.map(line => line.price*line.quantity).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
        job.formatted_total = formatCurrency(job.total)
    }

    const updateScopeDays = () => {
        job.scopes.map(scope => scope.days = parseInt(scope.lines.reduce((a, b) => a + b.days, 0)) ?? 0)
        job.days = job.scopes.map(scope => scope.days).reduce((a, b) => a + b, 0)
    }

    return {ref_line, ref_scope, addLine, addScope, removeLine, removeScope, lineTotal, scopeTotal, jobTotal, updateScopeDays}
}
