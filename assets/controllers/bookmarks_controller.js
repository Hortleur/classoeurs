import { Controller} from "@hotwired/stimulus";
import AlertController  from './alert_controller.js'

export default class extends Controller {
    static targets = ['bookButton']
    save(event) {
        console.log(event.target.dataset.postid)
        const postId = event.target.dataset.postId

        fetch(`/bookmarks/save/${postId}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                AlertController.emitAlert(data.message, 'success')
                if (data.message.includes('plus')){
                    this.bookButtonTarget.setAttribute('icon', 'material-symbols:bookmark-add')
                } else {
                    this.bookButtonTarget.setAttribute('icon', 'material-symbols-light:bookmark-remove')
                }
            })
            .catch(err => {
                AlertController.emitAlert('Une erreur s\' est produite', 'error')
            })
    }
}