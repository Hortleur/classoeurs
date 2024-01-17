import { Controller} from "@hotwired/stimulus";
import AlertController  from './alert_controller.js'

/* stimulusFetch: 'lazy' */
export default class PostController extends Controller {

    static targets = ['publishInput'];

    publish(event){
        const postId = Number(event.target.dataset.postId)

        console.log(postId)

        fetch(`/post/publish/${postId}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                AlertController.emitAlert(data.message, 'success')
            })
            .catch(err => {
                AlertController.emitAlert('Le post n\'a pas pu être mis à jour')
            })
    }
}