import { Controller} from "@hotwired/stimulus";

export default class extends Controller {
    save(event) {
        const postId = event.target.dataset.postId

        fetch(`/bookmarks/save/${postId}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                console.log(data.message)
            })
            .catch(err => {
                console.log(err)
            })
    }
}