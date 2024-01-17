import { Controller} from "@hotwired/stimulus";
import AlertController  from './alert_controller.js'

/* stimulusFetch: 'lazy' */
export default class CommentController extends Controller {
    static targets = ['commentInput', 'comments']

    async comment(event){
        if (this.commentInputTarget.value.length < 1){
            AlertController.emitAlert('Veuillez écrire un commentaire', 'error')
            return
        }

        const postId = Number(event.target.dataset.postId)
        const comment = this.commentInputTarget.value

        const data = {
            postId,
            comment
        }

        const params = new URLSearchParams(data)

        await fetch(`/comment/new?${params.toString()}`, {
            method: 'POST',
            headers: {
                'content-type': 'application/json'
            },
        })
            .then(res=>res.json())
            .then(data => {
                location.reload()
            })
            .catch(error=> {
                AlertController.emitAlert('une erreur est survenue', 'error')
            })

    }

    async deleteComment(event){
        const commentId = event.target.dataset.commentId

        await fetch(`/comment/delete/${commentId}`, {
            method: 'DELETE',
            'content-type': 'application/json'
        })
            .then(res => res.json())
            .then(data => {
                location.reload()
            })
            .catch(error => {
                AlertController.emitAlert(error, 'error')
            })
    }
}