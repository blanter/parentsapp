@extends('layouts.admin')

@section('title', 'Volunteer Mission Data')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'Volunteer Mission Analytics')

@section('content')
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="adm-card" style="padding: 24px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 54px; height: 54px; background: rgba(108, 136, 224, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="calendar-check" style="width: 26px; color: var(--db-purple);"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">This Week</div>
                    <div style="font-size: 28px; font-weight: 900; color: var(--db-text-dark); line-height: 1;">{{ $weeklyCompletions }}</div>
                </div>
            </div>
        </div>
        
        <div class="adm-card" style="padding: 24px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 54px; height: 54px; background: rgba(255, 107, 74, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="check-circle" style="width: 26px; color: var(--db-accent);"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Today</div>
                    <div style="font-size: 28px; font-weight: 900; color: var(--db-text-dark); line-height: 1;">{{ $todayCompletions }}</div>
                </div>
            </div>
        </div>
        
        <div class="adm-card" style="padding: 24px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 54px; height: 54px; background: rgba(255, 214, 75, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="users" style="width: 26px; color: var(--db-primary);"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Active Users</div>
                    <div style="font-size: 28px; font-weight: 900; color: var(--db-text-dark); line-height: 1;">{{ $activeUsersThisWeek }}</div>
                </div>
            </div>
        </div>
        
        <div class="adm-card" style="padding: 24px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 54px; height: 54px; background: rgba(34, 197, 94, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="award" style="width: 26px; color: #22C55E;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">All Time</div>
                    <div style="font-size: 28px; font-weight: 900; color: var(--db-text-dark); line-height: 1;">{{ $totalCompletions }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Activity Table -->
    <div class="adm-card">
        <div class="adm-card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h3 style="font-weight: 800; font-size: 18px; color: var(--db-text-dark); margin: 0;">Parent Activity Overview</h3>
                <div style="background: rgba(108, 136, 224, 0.1); color: var(--db-purple); padding: 6px 16px; border-radius: 12px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                    <i data-lucide="calendar" style="width: 14px;"></i>
                    <span>Current Week</span>
                </div>
            </div>
        </div>
        <div style="padding: 0;">
            <div class="adm-table-wrapper">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th style="padding-left: 24px;">Parent Name</th>
                            <th style="text-align: center;">Weekly Missions</th>
                            <th style="text-align: center;">Total Completed</th>
                            <th style="text-align: center;">Current Streak</th>
                            <th style="padding-right: 24px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userStats as $stat)
                            <tr>
                                <td style="padding-left: 24px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 42px; height: 42px; background: var(--db-purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            @if($stat['user']->avatar)
                                                <img src="{{ asset('avatars/' . $stat['user']->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i data-lucide="user" style="width: 20px; color: #fff;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: var(--db-text-dark); font-size: 14px;">{{ $stat['user']->name }}</div>
                                            <div style="font-size: 11px; color: #9CA3AF; font-weight: 600;">{{ $stat['user']->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(108, 136, 224, 0.1); color: var(--db-purple); padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 14px;">
                                        <i data-lucide="check-square" style="width: 16px;"></i>
                                        {{ $stat['weekly_completions'] }}
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; font-size: 14px; color: var(--db-text-dark);">{{ $stat['total_completions'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    @if($stat['current_streak'] > 0)
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 107, 74, 0.1); color: var(--db-accent); padding: 6px 14px; border-radius: 10px; font-weight: 800; font-size: 13px;">
                                            <i data-lucide="flame" style="width: 16px;"></i>
                                            {{ $stat['current_streak'] }} days
                                        </div>
                                    @else
                                        <span style="font-size: 12px; color: #9CA3AF; font-weight: 600;">No streak</span>
                                    @endif
                                </td>
                                <td style="padding-right: 24px; text-align: right;">
                                    <button class="btn-warning" onclick="viewUserDetail('{{ $stat['user']->id }}', '{{ $stat['user']->name }}')"
                                        style="border-radius: 12px; padding: 10px 20px; font-weight: 800; font-size: 12px; border: none; box-shadow: 0 4px 0px #e6a51d; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                                        <i data-lucide="eye" style="width: 14px; stroke-width: 3px;"></i> 
                                        <span>View Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 60px 20px;">
                                    <i data-lucide="inbox" style="width: 48px; height: 48px; color: #9CA3AF; margin-bottom: 15px;"></i>
                                    <p style="font-weight: 700; color: #9CA3AF; margin: 0;">No volunteer data available yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal User Detail -->
    <div id="userDetailModal" class="modal" style="display: none;">
        <div class="modal-content adm-modal-content" style="position: relative; overflow: hidden;">
            <div style="background: var(--db-purple); padding: 24px 30px; border-radius: 0; border: none;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
                    <div style="flex: 1;">
                        <h2 style="font-weight: 800; font-size: 20px; margin: 0; color: #fff; line-height: 1.3;">Volunteer
                            Mission Detail</h2>
                        <p id="modalUserName"
                            style="font-size: 13px; opacity: 0.85; margin: 8px 0 0 0; font-weight: 600; color: #fff;"></p>
                    </div>
                    <button class="close-btn" onclick="closeUserDetailModal()"
                        style="background: rgba(255,255,255,0.2); border: none; color: #fff; width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; cursor: pointer; flex-shrink: 0;">
                        <i data-lucide="x" style="width: 20px;"></i>
                    </button>
                </div>
            </div>
            <div style="padding: 30px; max-height: 70vh; overflow-y: auto; background: #FAFBFC;">
                <div id="userMissionList">
                    <!-- Mission details will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const userStatsData = @json($userStats);
        const missionsData = @json($missions);

        function viewUserDetail(userId, userName) {
            const userStat = userStatsData.find(u => u.user.id == userId);
            if (!userStat) return;

            document.getElementById('modalUserName').innerText = `${userName} • This Week Activity`;

            const missionList = document.getElementById('userMissionList');
            missionList.innerHTML = '';

            if (userStat.weekly_completions === 0) {
                missionList.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px; opacity: 0.3;">
                            <i data-lucide="calendar-x" style="width: 48px; height: 48px; margin-bottom: 15px;"></i>
                            <p style="font-weight: 800; font-size: 16px;">No missions completed this week.</p>
                        </div>
                    `;
            } else {
                missionsData.forEach(mission => {
                    const completions = userStat.completions[mission.id] || [];

                    if (completions.length > 0) {
                        const card = document.createElement('div');
                        card.className = 'adm-gn-progress-item shadow-sm';
                        card.style.marginBottom = '20px';

                        const iconMap = {
                            'peternakan': 'dog',
                            'perkebunan': 'flower',
                            'karya': 'palette'
                        };

                        let icon = 'star';
                        for (let key in iconMap) {
                            if (mission.name.toLowerCase().includes(key)) {
                                icon = iconMap[key];
                                break;
                            }
                        }

                        card.innerHTML = `
                                <div class="adm-gn-progress-content">
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                        <div style="width: 50px; height: 50px; background: rgba(108, 136, 224, 0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                            <i data-lucide="${icon}" style="width: 24px; color: var(--db-purple);"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 800; color: var(--db-text-dark); font-size: 16px;">${mission.name}</div>
                                            <div style="font-size: 12px; color: #9CA3AF; font-weight: 600; margin-top: 4px;">Completed ${completions.length} time(s) this week</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        ${completions.map(c => `
                                            <div style="background: rgba(34, 197, 94, 0.1); color: #22C55E; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                                <i data-lucide="check" style="width: 12px;"></i>
                                                ${new Date(c.completed_at).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' })}
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;
                        missionList.appendChild(card);
                    }
                });
            }

            document.getElementById('userDetailModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function closeUserDetailModal() {
            document.getElementById('userDetailModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function (event) {
            const modal = document.getElementById('userDetailModal');
            if (event.target == modal) closeUserDetailModal();
        }
    </script>
@endsection