<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogPostDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_published_blog_posts(): void
    {
        BlogPost::create([
            'title' => 'Sabah Sunrise Guide',
            'excerpt' => 'Best sunrise viewpoints for first-time visitors.',
            'content' => 'Watch the sun rise over the mountains.',
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        BlogPost::create([
            'title' => 'Draft Blog Post',
            'excerpt' => 'This should stay hidden.',
            'content' => 'Hidden content.',
            'published_at' => now()->subDay(),
            'is_published' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sabah Sunrise Guide')
            ->assertDontSee('Draft Blog Post');
    }

    public function test_published_blog_post_page_can_be_viewed(): void
    {
        $blogPost = BlogPost::create([
            'title' => 'Sabah Food Stops',
            'excerpt' => 'Tasty local places around Kota Kinabalu.',
            'content' => 'Start with local seafood, then explore the night market.',
            'social_media_url' => 'https://www.instagram.com/p/sample-post',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'published_at' => now()->subHour(),
            'is_published' => true,
        ]);

        $this->get(route('blog.show', $blogPost))
            ->assertOk()
            ->assertSee('Sabah Food Stops')
            ->assertSee('Start with local seafood')
            ->assertSee('View Social Media Post')
            ->assertSee('youtube.com/embed/dQw4w9WgXcQ', false);
    }

    public function test_unpublished_or_scheduled_blog_post_page_is_not_public(): void
    {
        $draftPost = BlogPost::create([
            'title' => 'Draft Update',
            'content' => 'Not public yet.',
            'published_at' => now()->subHour(),
            'is_published' => false,
        ]);

        $scheduledPost = BlogPost::create([
            'title' => 'Scheduled Update',
            'content' => 'Coming tomorrow.',
            'published_at' => now()->addDay(),
            'is_published' => true,
        ]);

        $this->get(route('blog.show', $draftPost))->assertNotFound();
        $this->get(route('blog.show', $scheduledPost))->assertNotFound();
    }

    public function test_admin_can_create_a_published_blog_post(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.blog-posts.store'), [
            'title' => 'Daily Sabah Update',
            'excerpt' => 'Fresh updates for today.',
            'content' => 'Today we launched a new Sabah travel guide.',
            'social_media_url' => 'https://www.facebook.com/universaleden/posts/sample',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'is_published' => '1',
            'cover_image' => UploadedFile::fake()->image('daily-update.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Daily Sabah Update',
            'social_media_url' => 'https://www.facebook.com/universaleden/posts/sample',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
        ]);

        Storage::disk('public')->assertExists(BlogPost::firstOrFail()->cover_image_path);
    }
}
