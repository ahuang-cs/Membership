const sections = {
  'confirmed-infection': 'Confirmed infection',
  'exposure': 'Recent exposure',
  'underlying-medical': 'Underlying medical conditions',
  'live-with-shielder': 'Lives with shielder',
  'understand-return': 'Understands return to training protocols',
  'able-to-train': 'Able to train',
  'sought-advice': 'Sought advice',
  'advice-received': 'Advice received',
}

const options = document.getElementById('js-opts').dataset;

let table = document.getElementById('table-area');
if (table) {
  table.addEventListener('click', (event) => {
    if (event.target.tagName == 'BUTTON' && event.target.classList.contains('review-button')) {
      let button = event.target;
      let reviewId = button.dataset.reviewId;

      // Get review
      let review = JSON.parse(button.dataset.reviewDocument);

      document.getElementById('reviewModalLabel').textContent = 'Review submission for ' + button.dataset.memberName;
      body = document.getElementById('reviewModalBody');
      body.innerHTML = '';

      let rowNode, col1, col2;
      rowNode = document.createElement('DL');
      rowNode.classList.add('row', 'mb-0');

      let keys = Object.keys(review.form);
      let md = window.markdownParser;

      for (let i = 0; i < keys.length; i++) {
        col1 = document.createElement('DT');
        col1.classList.add('col-sm-3');
        col1.textContent = sections[keys[i]];
        col2 = document.createElement('DD');
        col2.classList.add('col-sm-9');

        console.log(review.form[keys[i]]);

        let stateTextNode = document.createTextNode('');
        if (review.form[keys[i]].state) {
          stateTextNode.textContent = 'Yes';
        } else {
          stateTextNode.textContent = 'No';
        }
        col2.appendChild(stateTextNode);

        if (review.form[keys[i]].notes) {
          let card = document.createElement('DIV');
          card.classList.add('card', 'card-body', 'p-2', 'pb-0');
          let html = md.render(review.form[keys[i]].notes);
          card.innerHTML = html;
          col2.appendChild(card);
        }

        rowNode.appendChild(col1);
        rowNode.appendChild(col2);
      }

      body.appendChild(rowNode);

      // Set id on buttons
      document.getElementById('approve-button').dataset.submission = reviewId;
      document.getElementById('reject-button').dataset.submission = reviewId;

      $('#reviewModal').modal('show');
    }
  });
}

document.getElementById('reviewModalFooter').addEventListener('click', (event) => {
  if (event.target.tagName == 'BUTTON' && event.target.classList.contains('review-confirm-button')) {
    let button = event.target;

    // Get type
    let type = button.dataset.action;
    let submission = button.dataset.submission;

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        let json = JSON.parse(this.responseText);
        if (json.status == 200) {
          location.reload();
        } else {
          alert(json.error);
        }
      } else if (this.readyState == 4) {
        // Not ok
        alert('An error occurred. Your action was not saved.');
      }
    }
    req.open('POST', options.ajaxUrl, true);
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.send('submission=' + encodeURI(submission) + '&type=' + encodeURI(type));
  }
});