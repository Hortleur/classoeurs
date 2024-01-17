import { Controller} from "@hotwired/stimulus";
import AlertController  from './alert_controller.js'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['bookButton', 'bookCount']
    async save(event) {
        const postId = Number(event.target.dataset.postId)

        await fetch(`/bookmarks/save/${postId}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                AlertController.emitAlert(data.message, 'success')
                if (!data.bookmarked){
                    this.bookButtonTarget.setAttribute('icon', 'material-symbols:bookmark-add')
                    this.bookButtonTarget.classList.remove('text-error')
                    this.bookButtonTarget.classList.add('text-success')
                } else {
                    this.bookButtonTarget.setAttribute('icon', 'material-symbols-light:bookmark-remove')
                    this.bookButtonTarget.classList.remove('text-success')
                    this.bookButtonTarget.classList.add('text-error')
                }
                this.bookCountTarget.innerText = data.bookmarkedCount
            })
            .catch(err => {
                AlertController.emitAlert('Une erreur s\' est produite', 'error')
            })
    }
}