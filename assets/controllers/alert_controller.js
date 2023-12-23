import { Controller} from "@hotwired/stimulus";
export default class AlertController extends Controller {
    connect(){
        this.element.classList.add('hidden')
    }
    static emitAlert(message, type) {
        this.element = document.querySelector('.alert')
        this.element.classList.remove('hidden')
        if (type !== 'success'){
            this.element.classList.add(`alert-error`)
        } else {
            this.element.classList.add(`alert-success`)
        }
        this.element.textContent = message

        setTimeout(()=>{
            this.element.classList.add('hidden')
        }, 2000)
    }
}