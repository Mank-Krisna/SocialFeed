<div x-data="storyPlayer()" x-init="init()" class="relative">
    <!-- Fullscreen Modal -->
    <div x-show="$wire.isOpen" 
         x-transition.opacity.duration.300ms
         class="fixed inset-0 z-50 bg-black flex flex-col"
         style="display: none;"
         x-cloak>

        <!-- Progress Bars -->
        <div class="flex gap-1 p-2 pt-4">
            <template x-for="(story, index) in $wire.stories" :key="index">
                <div class="flex-1 h-1 bg-white/30 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-100 ease-linear"
                         :style="'width: ' + getSegmentWidth(index) + '%'"></div>
                </div>
            </template>
        </div>

        <!-- Header -->
        <div class="absolute top-12 left-0 right-0 flex items-center justify-between px-4 z-10 text-white">
            <div class="flex items-center gap-3" x-show="$wire.currentStory">
                <div class="flex items-center gap-2" x-show="$wire.currentStory">
                    <img :src="$wire.currentStory.user?.avatar_url || 'https://ui-avatars.com/api/?name=U'" 
                         class="w-8 h-8 rounded-full border border-white/50 object-cover">
                    <div>
                        <p class="text-sm font-semibold" x-text="$wire.currentStory.user?.username || ''"></p>
                        <p class="text-xs opacity-70" x-text="timeAgo($wire.currentStory.created_at)"></p>
                    </div>
                </div>
            </div>
            <button @click="$wire.closeViewer()" class="text-white text-3xl leading-none p-2 hover:opacity-70">&times;</button>
        </div>

        <!-- Media Content -->
        <div class="flex-1 flex items-center justify-center relative overflow-hidden"
             @touchstart="handleTouchStart($event)" @touchend="handleTouchEnd($event)">
            
            <div x-show="$wire.currentStory" class="w-full h-full flex items-center justify-center">
                <div x-show="isVideo($wire.currentStory?.media_path)">
                    <video :src="'/storage/' + $wire.currentStory?.media_path" 
                           class="max-w-full max-h-full object-contain"
                           autoplay muted playsinline
                           @ended="$wire.nextStory()"></video>
                </div>
                <div x-show="!isVideo($wire.currentStory?.media_path)">
                    <img :src="'/storage/' + $wire.currentStory?.media_path" 
                         class="max-w-full max-h-full object-contain">
                </div>
            </div>
        </div>

        <!-- Caption -->
        <div class="absolute bottom-8 left-0 right-0 px-4 text-center z-10" 
             x-show="$wire.currentStory?.caption">
            <p class="text-white text-sm bg-black/50 inline-block px-4 py-2 rounded-xl" 
               x-text="$wire.currentStory?.caption || ''"></p>
        </div>

        <!-- Tap Zones -->
        <div class="absolute inset-y-0 left-0 w-1/4 z-20 cursor-pointer" @click="$wire.prevStory()"></div>
        <div class="absolute inset-y-0 right-0 w-1/4 z-20 cursor-pointer" @click="$wire.nextStory()"></div>
    </div>
</div>

<script>
function storyPlayer() {
    return {
        timer: null,
        progress: 0,
        duration: 5000,
        isPaused: false,

        init() {
            this.$wire.$on('story-advanced', () => this.resetTimer());
            
            this.$watch('$wire.isOpen', value => {
                if (value) this.startTimer();
                else this.clearTimer();
            });
        },

        getSegmentWidth(index) {
            if (index < this.$wire.currentStoryIndex) return 100;
            if (index === this.$wire.currentStoryIndex) return this.progress;
            return 0;
        },

        startTimer() {
            this.clearTimer();
            this.progress = 0;
            const interval = 50;
            const step = 100 / (this.duration / interval);
            
            this.timer = setInterval(() => {
                if (this.isPaused) return;
                this.progress += step;
                if (this.progress >= 100) {
                    this.clearTimer();
                    this.$wire.nextStory();
                }
            }, interval);
        },

        clearTimer() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
            this.progress = 0;
        },

        resetTimer() {
            this.startTimer();
        },

        handleTouchStart(e) {
            this.isPaused = true;
        },

        handleTouchEnd(e) {
            this.isPaused = false;
        },

        isVideo(path) {
            if (!path) return false;
            return /\.(mp4|mov|webm)$/i.test(path);
        },

        timeAgo(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const diff = Math.floor((new Date() - date) / 1000);
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + 'm';
            if (diff < 86400) return Math.floor(diff / 3600) + 'j';
            return Math.floor(diff / 86400) + 'h';
        }
    }
}
</script>
