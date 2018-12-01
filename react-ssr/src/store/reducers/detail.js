import { detail_action } from '../actions';

export default function (state = {}, action) {
    let result = { ...state };
    switch (action.type) {
        case detail_action.ACTION_GET_CONTENT_DETAIL:
        case detail_action.ACTION_GET_CONTENT_EPISODES:
        case detail_action.ACTION_GET_CONTENT_BY_SLUG:
            result[action.type] = action.payload.data;
            break;
        default:
            break;
    }

    return result;
}