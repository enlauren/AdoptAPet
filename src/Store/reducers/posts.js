import * as aType from "../actions/actionTypes";

const initialState = {
  posts: [],
  loading: true,
  fetchPostsError: null,
  currentPageNumber: null
};

const reducer = (state = initialState, action) => {
  switch (action.type) {
    case aType.GET_POSTS_START:
      return {
        ...state,
        loading: true
      };
    case aType.GET_POSTS_SUCCESS:
      const kv = {
        ...action.postsData
      };
      return {
        ...state,
        loading: false,
        fetchPostsError: null,
        posts: state.posts.concat(kv)
      };
    case aType.GET_POSTS_FAIL:
      return {
        ...state,
        fetchPostsError: action.error,
        loading: false
      };
    default:
      return state;
  }
};

export default reducer;
