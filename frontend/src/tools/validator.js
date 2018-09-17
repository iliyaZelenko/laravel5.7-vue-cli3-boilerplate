import { Validator } from 'vee-validate'
import { vp } from '@/tools/helpers'

export const nicknameMaxSymbols = 32

// Прежде чем ставить required| в правила какому-то полю, подумайте будет ли использоваться похожее правило но без required|
export const rules = {
  name: 'required|alpha_spaces|max:30',
  // firstName: 'alpha|min:3|max:10',
  // lastName: 'alpha|min:3|max:15',
  // nickname: { required: true, regex: '^[а-яА-Яa-zA-ZЁё][а-яА-Яa-zA-Z0-9Ёё]*?([-_.][а-яА-Яa-zA-Z0-9Ёё]+){0,3}$', min: 3, max: nicknameMaxSymbols },
  // nicknameOrEmail: 'required',
  email: 'email',
  // emailLabel: 'min:3|max:20', // alpha_spaces
  // phoneLabel: 'min:3|max:20',
  password: 'required|min:5'
  // phone: 'numeric|min:9|max:15',
  // topics
  // 'topic-title': 'required|alpha_spaces|min:10|max:150',
  // 'topic-description': 'required', // |minLengthWithoutTags:150|maxLengthWithoutTags:3500
  // posts
  // 'post-message': 'required' // |minLengthWithoutTags:10|maxLengthWithoutTags:2000
}

export const validator = new Validator(rules)

// serverValidatorShowErrors
export const showServerError = ({ data }) => {
  const { errors } = data
  let message = ''

  if (errors) {
    for (let field in errors) {
      if (errors.hasOwnProperty(field)) {
        for (let error of errors[field]) {
          message += `${error}<br>`
        }
      }
    }
  } else {
    message = data.message
  }

  vp.$notify.error(message)
}
