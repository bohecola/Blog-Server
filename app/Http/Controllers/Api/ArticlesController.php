<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleListCollection;
use App\Http\Requests\Api\ArticleRequest;

class ArticlesController extends Controller
{
    // 1. 发表一篇博文
    public function store(ArticleRequest $request, Article $article)
    {
        // (1) 获取表单提交的数据，在数据库中相应的创建一篇博文
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        // (2) 根据创建的 ArticleResource 返回刚发表的这篇博文
        return new ArticleResource($article);
    }

    // 2. 查找某一篇博文
    public function show(Article $article, Request $request)
    {
        // (1) 根据 id 查询到这篇博文
        $result = $article->find($request->id);
        // (2) 根据创建的 ArticleResource 返回这篇博文
        if(!empty($result)) {
            return new ArticleResource($result);
        } else {
            abort(404, '页面不存在');
        }
    }

    // 3. 删除某一篇博文
    public function destroy(ArticleRequest $request, Article $article)
    {
        // (1) 根据 id 查询到这篇博文
        $result = $article->find($request->id);
        // (2) 判断用户是否有权限删除这篇博文
        $this->authorize('update', $result);
        // (3) 直接删除对应 id 的博文 (destroy 返会删除记录的条数) (delete 返会 Boolean 类型 true or false)
        $article->destroy($request->id);
        // (2) 返回删除对应的http状态码
        return response(null, 204);
    }

    // 4. 更新某一篇博文
    public function update(ArticleRequest $request, Article $article)
    {
        // (1) 设置博文可以更新的字段
        $attributes = $request->only(['title', 'content']);
        // (2) 根据 id 查询到这篇博文
        $result = $article->find($request->id);
        // (3) 判断用户是否有权限修改这篇博文
        $this->authorize('update', $result);
        // (4) 更新博文对应设置的字段
        $result->update($attributes);
        // (5) 根据创建的 ArticleResource 返回更新后的这篇博文
        return new ArticleResource($result);
    }

    // 5. 获取当前用户所有博文
    public function collection(Article $article, Request $request)
    {
        // (1) 获取所有博文
        $articles = $article->where('user_id', '=', $request->user_id)->orderBy('created_at', 'desc')->get();
        // $articles = $article->paginate(5);

        // (2) 根据创建的 ArticleListCollection 返回包含所有博文的一个集合
        return new ArticleListCollection($articles);
    }

    // 5. 获取所有博文
    public function all(Article $article, Request $request)
    {
        // (1) 获取所有博文
        $articles = $article->orderBy('created_at', 'desc')->get();
        // $articles = $article->paginate(5);

        // (2) 根据创建的 ArticleListCollection 返回包含所有博文的一个集合
        return new ArticleListCollection($articles);
    }
}
